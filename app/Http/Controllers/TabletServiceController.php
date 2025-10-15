<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Tablet;
use App\Models\Baker;
use App\Models\Production;
use App\Models\Order;
use App\Models\ProductConnect;
use App\Models\Sold;
use App\Models\Employee;

use Carbon\Carbon;
use Google\Service\Sheets;
use Google\Client;

class TabletServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.tablet');
    }

    public function fetch()
    {
        $branch = Branch::with('products')->where('id', auth('tablet')->user()->branch_id)->first();
        $bakers = Baker::where('branch_id', auth('tablet')->user()->branch_id)->where('deleted', false)->get();
        $productions = Production::with(['baker', 'product'])
                ->where('branch_id', auth('tablet')->user()->branch_id)
                ->where('date', Carbon::now()->format("Y-m-d"))
                ->get();

        return [
            'ok' => true,
            'data' => $productions,
            'products' => $branch->products,
            'bakers' => $bakers,
        ];
    }


    public function productionProduct(Request $request)
    {
        Production::create([
            'branch_id' => auth('tablet')->user()->branch_id,
            'count' => $request->count,
            'product_id' => $request->product_id,
            'baker_id' => $request->baker_id,
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        $baker = Baker::find($request->baker_id);
        $branch = Branch::find(auth('tablet')->user()->branch_id);
        $product = Product::find($request->product_id);

        $this->appendRow([
            $baker->name,
            $branch->name.' '.$branch->location,
            Carbon::now()->format('Y-m-d'),
            $product->name, $request->count
        ]);

        return response()->json($this->fetch());
    }

    public function appendRow($data)
    {
        // 1️⃣ Google Client sozlash

        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/novvoyxona-key.json'));
        $client->addScope(Sheets::SPREADSHEETS);

        // 2️⃣ Sheets servisini yaratish
        $service = new Sheets($client);

        $spreadsheetId = '1_dlP245nZ5-fRE0nkkobVbUAZLImQi0FYxb2SNtsvgo'; // sizning Google Sheet ID
        $range = 'A2:E2'; // qaysi joyga yoziladi

        // 3️⃣ Yoziladigan ma’lumotlar
        $values = [$data];

        $body = new \Google\Service\Sheets\ValueRange([
            'values' => $values
        ]);

        // 4️⃣ Append qilish
        $params = [
            'valueInputOption' => 'USER_ENTERED'
        ];

        $result = $service->spreadsheets_values->append(
            $spreadsheetId,
            $range,
            $body,
            $params
        );

        return true;

        return response()->json([
            'status' => 'success',
            'updatedCells' => $result->getUpdates()->getUpdatedCells(),
        ]);
    }

    public function productionFetch()
    {
        return response()->json($this->fetch());
    }

    public function orders()
    {
        return response()->json([
            'ok' => true,
            'data' => Order::where('branch_id', auth('tablet')->user()->branch_id)->with(['branch', 'courier'])->get(),
        ]);
    }

    public function sales()
    {
        return response()->json([
            'ok' => true,
            'data' => Sold::where('branch_id', auth('tablet')->user()->branch_id)->with('branch')->get(),
        ]);
    }

    public function products()
    {
        $branchId = auth('tablet')->user()->branch_id;

        $productConnects = ProductConnect::where('branch_id', $branchId)
            ->with('product')
            ->whereHas('product', function($query) {
                $query->where('deleted', false);
            })
            ->get();

        $products = $productConnects->map(function ($item) {
            return $item->product;
        })->filter();

        return response()->json([
            'ok' => true,
            'data' => $products,
        ]);
    }

    public function employees()
    {
        $data = Employee::where('status', '!=', 'deleted')->where('branch_id', auth('tablet')->user()->branch_id)->get();

        return response()->json([
            'ok' => true,
            'data' => $data,
        ]);
    }


    public function changeEmployeeStatus($id)
    {
        $employee = Employee::find($id);
        $employee->status = $employee->status == 'online' ? 'offline' : 'online';
        $employee->save();

        $data = Employee::where('status', '!=', 'deleted')->where('branch_id', auth('tablet')->user()->branch_id)->get();

        return response()->json([
            'ok' => true,
            'data' => $data,
        ]);
    }

    public function createOrder(Request $request)
    {
        $order = Order::create([
            "branch_id" => auth('tablet')->user()->branch_id,
            "customer_name" => $request->customer_name,
            "customer_phone" => $request->customer_phone,
            "amount" => $request->amount,
            "location" => $request->location,
            "order_time" => Carbon::now()->format("Y-m-d"),
            "details" => json_encode($request->details),
            "coordinates" => json_encode($request->coordinates),
            'products_count' => $request->products_count,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Order created successfully',
            'data' => Order::where('branch_id', auth('tablet')->user()->branch_id)->with('branch')->get(),
        ], 201);
    }

    public function createSale(Request $request)
    {
        $order = Sold::create([
            "branch_id" => auth('tablet')->user()->branch_id,
            "amount" => $request->amount,
            "date" => Carbon::now()->format("Y-m-d"),
            "details" => json_encode($request->details),
            "products_count" => $request->products_count,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Order created successfully',
            'data' => Sold::where('branch_id', auth('tablet')->user()->branch_id)->with('branch')->get(),
        ], 201);
    }

    public function analytics()
    {
        $branchId = auth('tablet')->user()->branch_id;
        $now = Carbon::now()->format('Y-m-d');

        $productions_count = Production::where('date', $now)->where('branch_id', $branchId)->sum('count');
        $sold_count = Sold::where('date', $now)->where('branch_id', $branchId)->sum('products_count');
        $orders_count = Order::where('order_time', $now)->where('branch_id', $branchId)->sum('products_count');
        $benefit = Sold::where('date', $now)->where('branch_id', $branchId)->sum('amount') + Order::where('order_time', $now) ->where('branch_id', $branchId) ->sum('amount');


        $orders = Order::where('branch_id', auth('tablet')->user()->branch_id)->with('courier')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'ok' => true,
            'data' => [
                'productions' => $productions_count,
                'sold' => $sold_count,
                'orders' => $orders_count,
                'benefit' => $benefit,
                'remaining' => $productions_count - ($sold_count + $orders_count)
            ],
            'orders' => $orders,
        ]);
    }

    public function acceptance(Request $request)
    {
        Order::where('id', $request->id)->update([ 'status' => $request->status ]);
        $orders = Order::where('branch_id', auth('tablet')->user()->branch_id)->with('courier')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'ok' => true,
            'data' => $orders,
        ]);
    }


    public function me()
    {
        return response()->json([
            'ok' => true,
            'data' => Tablet::where('id', auth('tablet')->user()->id)->with('branch')->first(),
        ]);
    }

}
