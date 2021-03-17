<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Mentorship;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Response;


class MentorshipController extends Controller
{

    /**
     * Display a listing of Orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mentorships = Mentorship::get();
        return view('backend.mentorships.index', compact('mentorships'));
    }

    /**
     * Display a listing of Orders via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $mentorship = Mentorship::query()->orderBy('updated_at', 'desc');

        return DataTables::of($mentorship->get())
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($request) {
                $view = "";

                $view = view('backend.datatable.action-view')
                    ->with(['route' => route('admin.orders.show', ['order' => $q->id])])->render();

                return $view;

            })
            ->addColumn('items', function ($q) {
                $items = "";
                foreach ($q->order->items as $key => $item) {
                    if($item->item != null){
                        $key++;
                        $items .= $key . '. ' . $item->item->title . "<br>";
                    }

                }
                return $items;
            })
            ->addColumn('user_email', function ($q) {
                return $q->user->email;
            })
            ->addColumn('date', function ($q) {
                return $q->updated_at->diffforhumans();
            })
            ->addColumn('teacher', function ($q) {
                return $q->teacher->full_name;
            })
            ->rawColumns(['items', 'actions'])
            ->make();
    }

    /**
     * Complete Order manually once payment received.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
//    public function complete(Request $request)
//    {
//        $order = Order::findOrfail($request->order);
//        $order->status = 1;
//        $order->save();
//
//        (new EarningHelper)->insert($order);
//
//        //Generating Invoice
//        generateInvoice($order);
//
//        foreach ($order->items as $orderItem) {
//            //Bundle Entries
//            if($orderItem->item_type == Bundle::class){
//               foreach ($orderItem->item->courses as $course){
//                   $course->students()->attach($order->user_id);
//               }
//            }
//            $orderItem->item->students()->attach($order->user_id);
//        }
//        return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
//    }

    /**
     * Show Order from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
//    public function show($id)
//    {
//        $order = Order::findOrFail($id);
//        return view('backend.orders.show', compact('order'));
//    }

    /**
     * Remove Order from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
//    public function destroy($id)
//    {
//
//        $order = Order::findOrFail($id);
//        $order->items()->delete();
//        $order->delete();
//        return redirect()->route('admin.orders.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
//    }

    /**
     * Delete all selected Orders at once.
     *
     * @param Request $request
     */
//    public function massDestroy(Request $request)
//    {
//        if (!Gate::allows('course_delete')) {
//            return abort(401);
//        }
//        if ($request->input('ids')) {
//            $entries = Order::whereIn('id', $request->input('ids'))->get();
//            foreach ($entries as $entry) {
//                if ($entry->status = 1) {
//                    foreach ($entry->items as $item) {
//                        $item->course->students()->detach($entry->user_id);
//                    }
//                    $entry->items()->delete();
//                    $entry->delete();
//                }
//            }
//        }
//    }


}
