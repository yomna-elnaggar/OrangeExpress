<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoicDetailseResource;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:manager');
    }

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);
        $search = $request->input('search');

        $invoices = Invoice::with(['vendor', 'driver'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('vendor', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->date, function ($q) use ($request) {
                $q->where('date', '>=', $request->date);
            })
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'code' => 200,
            'status' => true,
            'data' => InvoiceResource::collection($invoices) ,
            'pagination' => [
                'current_page' => $invoices->currentPage(),
                'last_page' => $invoices->lastPage(),
                'per_page' => $invoices->perPage(),
                'total' => $invoices->total(),
            ],
            'message' => 'Invoices retrieved successfully',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => ['required', Rule::exists('users', 'id')->where('role', 'vendor')],
            'driver_id' => ['required', Rule::exists('users', 'id')->where('role', 'driver')],
            'cobon_number' => 'nullable|string|unique:invoices',
            'receiver' => 'nullable|string',
            'receiver_phone' => 'nullable|string',
            'emira' => 'nullable|string',
            'area' => 'nullable|string',
            'order_fees' => 'nullable|numeric',
            'delivery_fees' => 'nullable|numeric',
            'total' => 'nullable|numeric',
            'date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->all();

        $invoice = Invoice::create($data);

        return response()->json([
            'code' => 201,
            'status' => true,
            'data' =>  new InvoiceResource($invoice),
            'message' => 'Invoice created successfully',
        ], 201);
    }

    public function show($id)
    {
        $invoice = Invoice::with(['vendor', 'driver'])->find($id);

        if (!$invoice) {
            return response()->json([
                'code' => 404,
                'status' => false,
                'message' => 'Invoice not found',
            ], 404);
        }

        return response()->json([
            'code' => 200,
            'status' => true,
            'data' => new InvoiceResource($invoice) ,
        ]);
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'code' => 404,
                'status' => false,
                'message' => 'Invoice not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'vendor_id' => ['required', Rule::exists('users', 'id')->where('role', 'vendor')],
            'driver_id' => ['required', Rule::exists('users', 'id')->where('role', 'driver')],
            'cobon_number' => 'nullable|string|unique:invoices,cobon_number,' . $id,
            'receiver' => 'nullable|string',
            'receiver_phone' => 'nullable|string',
            'emira' => 'nullable|string',
            'area' => 'nullable|string',
            'order_fees' => 'nullable|numeric',
            'delivery_fees' => 'nullable|numeric',
            'total' => 'nullable|numeric',
            'date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->all();

        $invoice->update($data);

        return response()->json([
            'code' => 200,
            'status' => true,
            'data' => new InvoiceResource($invoice) ,
            'message' => 'Invoice updated successfully',
        ]);
    }

    public function destroy($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'code' => 404,
                'status' => false,
                'message' => 'Invoice not found',
            ], 404);
        }

        $invoice->delete();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Invoice deleted successfully',
        ]);
    }

    public function invoicesDetails(Request $request)
    {
        
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);
        $search = $request->input('search');

        $invoices = Invoice::with(['vendor', 'driver'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('vendor', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->date, function ($q) use ($request) {
                $q->where('date', '>=', $request->date);
            })
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'code' => 200,
            'status' => true,
            'data' => InvoicDetailseResource::collection($invoices) ,
            'pagination' => [
                'current_page' => $invoices->currentPage(),
                'last_page' => $invoices->lastPage(),
                'per_page' => $invoices->perPage(),
                'total' => $invoices->total(),
            ],
            'message' => 'Invoices retrieved successfully',
        ]);
    }


}

