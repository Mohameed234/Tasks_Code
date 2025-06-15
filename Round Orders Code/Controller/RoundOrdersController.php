<?php

namespace App\Http\Controllers;

use App\Models\RoundOrder;
use App\Models\InnerLocation;
use App\Models\Checklists;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RoundOrdersController extends Controller
{
    /**
     * Display a listing of round orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roundOrders = RoundOrder::with(['innerLocation', 'checklist', 'technicianUser', 'departmentData'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $checklists = Checklists::orderBy('name')->get();
        return view('round-orders.index', compact('roundOrders', 'checklists'));
    }

    /**
     * Show the form for creating a new round order.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roundOrder = new RoundOrder();
        $locations = InnerLocation::orderBy('name')->get();
        $checklists = Checklists::orderBy('name')->get();
        $users = User::orderBy('first_name')->orderBy('last_name')->get();
        $departments = Department::orderBy('name')->get();
        return view('round-orders.form', compact('roundOrder', 'locations', 'checklists', 'users', 'departments'));
    }

    /**
     * Store a newly created round order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required|exists:inner_locations,id',
            'type' => 'required|string|in:Daily,Weekly,Monthly,3 Month,6 Month',
            'checklist' => 'required|exists:checklists,id',
            'description' => 'nullable|string',
            'attachment' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', // 10MB max
            'status' => 'required|string|in:pending,in_progress,completed',
            'technician' => 'required|exists:users,id',
            'department' => 'required|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'location' => $request->input('location'),
            'type' => $request->input('type'),
            'checklist' => $request->input('checklist'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'technician' => $request->input('technician'),
            'department' => $request->input('department'),
        ];

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
    
            $destinationPath = public_path('uploads/round-orders'); // full path like public/uploads/round-orders
            $filename = time() . '_' . $file->getClientOriginalName(); // optional: rename the file

            $file->move($destinationPath, $filename); // move file to the destination

            $data['attachment'] = 'uploads/round-orders/' . $filename; // store relative path i
        }

        RoundOrder::create($data);

        return redirect()->route('round-orders.index')
            ->with('success', 'Round order created successfully.');
    }

    /**
     * Display the specified round order.
     *
     * @param  \App\Models\RoundOrder  $roundOrder
     * @return \Illuminate\Http\Response
     */
    public function show(RoundOrder $roundOrder)
    {
        $roundOrder->load(['innerLocation', 'checklist.questions']);
        $checklists = Checklists::orderBy('name')->get();
        return view('round-orders.show', compact('roundOrder', 'checklists'));
    }

    /**
     * Show the form for editing the specified round order.
     *
     * @param  \App\Models\RoundOrder  $roundOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(RoundOrder $roundOrder)
    {
        $locations = InnerLocation::orderBy('name')->get();
        $checklists = Checklists::orderBy('name')->get();
        $users = User::orderBy('first_name')->orderBy('last_name')->get();
        $departments = Department::orderBy('name')->get();
        return view('round-orders.form', compact('roundOrder', 'locations', 'checklists', 'users', 'departments'));
    }

    /**
     * Update the specified round order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RoundOrder  $roundOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RoundOrder $roundOrder)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required|exists:inner_locations,id',
            'type' => 'required|string|in:Daily,Weekly,Monthly,3 Month,6 Month',
            'checklist' => 'required|exists:checklists,id',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
            'status' => 'required|string|in:pending,in_progress,completed',
            'technician' => 'required|exists:users,id',
            'department' => 'required|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'location' => $request->input('location'),
            'type' => $request->input('type'),
            'checklist' => $request->input('checklist'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'technician' => $request->input('technician'),
            'department' => $request->input('department'),
        ];

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($roundOrder->attachment) {
                Storage::delete($roundOrder->attachment);
            }
            $path = $request->file('attachment')->store('round-orders');
            $data['attachment'] = $path;
        }

        $roundOrder->update($data);

        return redirect()->route('round-orders.index')
            ->with('success', 'Round order updated successfully.');
    }

    /**
     * Remove the specified round order from storage.
     *
     * @param  \App\Models\RoundOrder  $roundOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoundOrder $roundOrder)
    {
        // Delete attachment if exists
        if ($roundOrder->attachment) {
            Storage::delete($roundOrder->attachment);
        }

        $roundOrder->delete();

        return redirect()->route('round-orders.index')
            ->with('success', 'Round order deleted successfully.');
    }
} 