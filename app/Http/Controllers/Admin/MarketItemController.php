<?php

namespace App\Http\Controllers\Admin;

use App\EnumsAndConsts\HttpStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\MarketItemCollection;
use App\Http\Resources\MarketItemResource;
use App\Models\MarketItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MarketItemController extends Controller
{
    /**
     * @param  Request  $request
     *
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MarketItem::query();

        $query->when($request->search, function (Builder $q) use ($request) {
            $q->where(function (Builder $q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
                $q->orWhere('type', 'like', "%{$request->search}%");
                $q->orWhereHas('user', function ($q) use ($request) {
                    $q->where('firstname', 'like', "%{$request->search}%");
                    $q->orWhereRaw("CONCAT_WS(' ', firstname, lastname) LIKE '%$request->search%'");
                });
            });
        })->when($request->order && is_array($request->order), function (Builder $q) use ($request) {
            foreach ($request->order as $by => $dir) {
                if (!in_array($by, ['desc', 'asc', 'null', null, ''])) {
                    $q->orderBy($by, mb_strtoupper($dir));
                }
            }
        })->latest();

        $marketplaces = $query->paginate($request->get('limit', '15'));

        return (new MarketItemCollection($marketplaces))->additional([
            'message' => HttpStatus::message(HttpStatus::OK),
            'status' => 'success',
            'status_code' => HttpStatus::OK,
        ])->response()->setStatusCode(HttpStatus::OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /**
         * @var \App\Models\User $user
         */
        $user = $request->user();

        $this->validate($request, [
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
            'price' => 'required|numeric',
            'name' => 'required|string|min:3|max:255',
            'type' => 'nullable|string|min:3|max:520',
            'grade' => 'nullable|string|min:1|max:55',
            'location' => 'nullable|string|min:1',
            'address' => ['required', 'string', 'min:5', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'quantity' => 'required|numeric|min:1',
            'quantity_unit' => 'required|string|min:1',
            'active' => 'nullable|boolean',
        ]);

        $item = $user->market()->make();
        $item->name = $request->name;
        $item->price = $request->price;
        $item->type = $request->type;
        $item->grade = $request->grade;
        $item->location = $request->location;
        $item->address = $request->address;
        $item->country = $request->country ?? 'Nigeria';
        $item->state = $request->state;
        $item->city = $request->city;
        $item->quantity = $request->quantity;
        $item->quantity_unit = $request->quantity_unit;
        $item->active = $request->active ?? true;
        $item->approved = true;
        $item->save();

        return (new MarketItemResource($item))->additional([
            'message' => __('Market Item ":0" has been created successfully', [$item->name]),
            'status' => 'success',
            'status_code' => HttpStatus::CREATED,
        ])->response()->setStatusCode(HttpStatus::CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(MarketItem $marketplace)
    {
        return (new MarketItemResource($marketplace))->additional([
            'message' => HttpStatus::message(HttpStatus::OK),
            'status' => 'success',
            'status_code' => HttpStatus::OK,
        ])->response()->setStatusCode(HttpStatus::OK);
    }

    /**
     * Update a resource in storage.
     */
    public function update(Request $request, MarketItem $marketplace)
    {
        $this->validate($request, [
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
            'price' => 'required|numeric',
            'name' => 'required|string|min:3|max:255',
            'type' => 'nullable|string|min:3|max:520',
            'grade' => 'nullable|string|min:1|max:55',
            'location' => 'nullable|string|min:1',
            'address' => ['required', 'string', 'min:5', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'quantity' => 'required|numeric|min:1',
            'quantity_unit' => 'required|string|min:1',
            'active' => 'nullable|boolean',
        ]);

        $item = $marketplace;
        $item->name = $request->name ?? $item->name;
        $item->price = $request->price ?? $item->price;
        $item->type = $request->type ?? $item->type;
        $item->grade = $request->grade ?? $item->grade;
        $item->location = $request->location ?? $item->location;
        $item->address = $request->address ?? $item->address;
        $item->country = $request->country ?? $item->country ?? 'Nigeria';
        $item->state = $request->state ?? $item->state;
        $item->city = $request->city ?? $item->city;
        $item->quantity = $request->quantity ?? $item->quantity;
        $item->quantity_unit = $request->quantity_unit ?? $item->quantity_unit;
        $item->active = $request->active ?? $item->active ?? true;
        $item->approved = $request->approved ?? $item->approved ?? true;
        $item->save();

        return (new MarketItemResource($item))->additional([
            'message' => __('Market Item ":0" has been updated successfully', [$item->name]),
            'status' => 'success',
            'status_code' => HttpStatus::CREATED,
        ])->response()->setStatusCode(HttpStatus::CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MarketItem $marketplace)
    {
        $marketplace->delete();

        return (new MarketItemResource($marketplace))->additional([
            'message' => __('Market Item ":0" has been deleted successfully', [$marketplace->name]),
            'status' => 'success',
            'status_code' => HttpStatus::ACCEPTED,
        ])->response()->setStatusCode(HttpStatus::ACCEPTED);
    }
}