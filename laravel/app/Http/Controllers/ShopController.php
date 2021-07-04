<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Services\ShopService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShopController extends Controller
{
  protected $shopService;


  public function __construct(ShopService $shopService)
  {
    $this->shopService = $shopService;
  }

  /**
   * @return \Illuminate\Http\JsonResponse
   */
  public function index()
  {
    return response()->json($this->shopService->all(Shop::RELATIONS_ARRAY));
  }


  public function create()
  {
  }

  /**
   * Shop a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * @param int $storeCode
   * @return \Illuminate\Http\JsonResponse
   */
  public function show(int $storeCode)
  {
    $shop = $this->shopService->findByCode(['branch'], $storeCode);
    return response()->json($shop);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(int $id)
  {
    //
  }

  /**
   * @param string $keyword
   * @return \Illuminate\Http\JsonResponse
   */
  public function filterByKeyword(string $keyword)
  {
    $shops = $this->shopService->filterByKeyword(['branch'], $keyword);
    return response()->json($shops);
  }
}
