<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\Bakery;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Validator;

/**
 * Class BakeryController
 * @author Varlent <varlent.422023028@civitas.ukrida.ac.id>
 */
class BakeryController extends Controller
{
    /** 
     * @OA\Get(
     *     path="/api/bakery",
     *     tags={"bakery"},
     *     summary="Display a listing of the items",
     *     operationId="index",
     *     @OA\Response(
     *         response=200,
     *         description="successful",
     *         @OA\JsonContent()
     *     ),
     *  @OA\Parameter(
     *      name="_page",
     *      in="query",
     *      description="current page",
     *      required=true,
     *      @OA\Schemas(
     *          type="integer",
     *          format="int64",
     *          example=1
     *      )
     *  ),
     *  @OA\Parameter(
     *      name="_limit",
     *      in="query",
     *      description="max item in a page",
     *      required=true,
     *      @OA\Schemas(
     *          type="integer",
     *          format="int64",
     *          example=10
     *      )
     *  ),
     *  @OA\Parameter(
     *      name="_search",
     *      in="query",
     *      description="word to search",
     *      required=false,
     *      @OA\Schemas(
     *          type="string"
     *      )
     *  ),
     *  @OA\Parameter(
     *      name="_publisher",
     *      in="query",
     *      description="search by publisher like name",
     *      required=false,
     *      @OA\Schemas(
     *          type="string"
     *      )
     *  ),
     *  @OA\Parameter(
     *      name="_sort_by",
     *      in="query",
     *      description="word to search",
     *      required=false,
     *      @OA\Schemas(
     *          type="integer",
     *          example="latest"
     *      )
     *  ),
     * )
     */
    public function index(Request $request)
    {
        try {
            $data['filter']       = $request->all();
            $page                 = $data['filter']['_page']  = (@$data['filter']['_page'] ? intval($data['filter']['_page']) : 1);
            $limit                = $data['filter']['_limit'] = (@$data['filter']['_limit'] ? intval($data['filter']['_limit']) : 1000);
            $offset               = ($page?($page-1)*$limit:0);
            $data['products']     = Bakery::whereRaw('1 = 1');
            
            if($request->get('_search')){
                $data['products'] = $data['products']->whereRaw('(LOWER(product_name) LIKE "%'.strtolower($request->get('_search')).'%")');
            }
            if($request->get('_type')){
                $data['products'] = $data['products']->whereRaw('LOWER(type) = "'.strtolower($request->get('_type')).'"');
            }
            if($request->get('_sort_by')){
            switch ($request->get('_sort_by')) {
                default:
                // case 'latest_baked':
                // $data['products'] = $data['products']->orderBy('bake_date','DESC');
                // break;
                case 'latest_added':
                $data['products'] = $data['products']->orderBy('created_at','DESC');
                break;
                case 'name_asc':
                $data['products'] = $data['products']->orderBy('product_name','ASC');
                break;
                case 'name_desc':
                $data['products'] = $data['products']->orderBy('product_name','DESC');
                break;
                case 'price_asc':
                $data['products'] = $data['products']->orderBy('price','ASC');
                break;
                case 'price_desc':
                $data['products'] = $data['products']->orderBy('price','DESC');
                break;
            }
            }
            $data['products_count_total']   = $data['products']->count();
            $data['products']               = ($limit==0 && $offset==0)?$data['products']:$data['products']->limit($limit)->offset($offset);
            // $data['products_raw_sql']       = $data['products']->toSql();
            $data['products']               = $data['products']->get();
            $data['products_count_start']   = ($data['products_count_total'] == 0 ? 0 : (($page-1)*$limit)+1);
            $data['products_count_end']     = ($data['products_count_total'] == 0 ? 0 : (($page-1)*$limit)+sizeof($data['products']));
           return response()->json($data, 200);

        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid data : {$exception->getMessage()}");
        }
    }
    

    /**
     * @OA\Post(
     *      path="/api/bakery",
     *      tags={"bakery"},
     *      summary="Store a newly created item",
     *      operationId="store",
     *      @OA\Response(
     *          response=400,
     *          description="Invalid input",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful",
     *          @OA\JsonContent()
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Request body description",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Bakery",
     *              example={
     *                  "product_name": "Croissant",
     *                  "category": "Pastry",
     *                  "cover": "https://imgs.search.brave.com/AMP2KygF7XMhNX1LKASc0nb654xvD9laxs07HNBzBzk/rs:fit:500:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5nZXR0eWltYWdl/cy5jb20vaWQvNzE5/MTY0NzYvcGhvdG8v/Y3JvaXNzYW50Lmpw/Zz9zPTYxMng2MTIm/dz0wJms9MjAmYz1W/Nnd4WHRxQVp6Uk5n/OHV6N0pDM1NkYmkw/SnFtbjQ1bms0VzE5/Q0VtbVA0PQ",
     *                  "description": "Croissant adalah sejenis roti berlapis-lapis berbentuk bulan sabit yang berasal dari Prancis. Terbuat dari adonan berlapis yang dicampur dengan mentega, croissant memiliki tekstur yang renyah di luar dan lembut di dalam.",
     *                  "price": 45000
     *              }
     *          )
     *      )
     * )
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_name' => 'required|unique:bakeries',
                'category' => 'required|max:100',
            ]);

            if ($validator->fails()) {
                throw new HttpException(400, $validator->messages()->first());
            }

            $bakery = new Bakery;
            $bakery->fill($request->all())->save();
            return $bakery;

        } catch (\Exception $exception) {
            throw new HttpException(400, "Invalid data: {$exception->getMessage()}");
        }
    }

    /**
     * @OA\Get(
     *      path="/api/bakeries/{id}",
     *      tags={"bakeries"},
     *      summary="Display the specified item",
     *      operationId="show",
     *      @OA\Response(
     *          response=404,
     *          description="Item not found",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid input",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of item that needs to be displayed",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        $bakery = Bakery::find($id);
        if (!$bakery) {
            throw new HttpException(404, "Item not found");
        }
        return $bakery;
    }

    /**
     * @OA\Put(
     *      path="/api/bakeries/{id}",
     *      tags={"bakery"},
     *      summary="Update the specified item",
     *      operationId="update",
     *      @OA\Response(
     *          response=404,
     *          description="Item not found",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid input",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of item that needs to be updated",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Request body description",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Bakery",
     *              example={
     *                  "product_name": "Croissant",
     *                  "category": "Pastry",
     *                  "cover": "https://imgs.search.brave.com/AMP2KygF7XMhNX1LKASc0nb654xvD9laxs07HNBzBzk/rs:fit:500:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5nZXR0eWltYWdl/cy5jb20vaWQvNzE5/MTY0NzYvcGhvdG8v/Y3JvaXNzYW50Lmpw/Zz9zPTYxMng2MTIm/dz0wJms9MjAmYz1W/Nnd4WHRxQVp6Uk5n/OHV6N0pDM1NkYmkw/SnFtbjQ1bms0VzE5/Q0VtbVA0PQ",
     *                  "description": "Croissant adalah sejenis roti berlapis-lapis berbentuk bulan sabit yang berasal dari Prancis. Terbuat dari adonan berlapis yang dicampur dengan mentega, croissant memiliki tekstur yang renyah di luar dan lembut di dalam.",
     *                  "price": 45000
     *              }
     *          )
     *      )
     * )
     */
    public function update(Request $request, $id)
    {
        $bakery = Bakery::find($id);
        if (!$bakery) {
            throw new HttpException(404, "Item not found");
        }

        try {
            $validator = Validator::make($request->all(), [
                'product_name' => 'required|unique:bakeries',
                'category' => 'required|max:100',
            ]);

            if ($validator->fails()) {
                throw new HttpException(400, $validator->messages()->first());
            }

            $bakery->fill($request->all())->save();
            return response()->json(['message' => 'Updated successfully'], 200);

        } catch (\Exception $exception) {
            throw new HttpException(400, "Invalid data: {$exception->getMessage()}");
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/bakeries/{id}",
     *      tags={"bakery"},
     *      summary="Remove the specified item",
     *      operationId="destroy",
     *      @OA\Response(
     *          response=404,
     *          description="Item not found",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid input",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of item that needs to be removed",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        $bakery = Bakery::findOrFail($id);
        if (!$bakery) {
            throw new HttpException(404, "Item not found");
        }

        try {
            $bakery->delete();
            return response()->json(['message' => 'Deleted successfully'], 200);

        } catch (\Exception $exception) {
            throw new HttpException(400, "Invalid data: {$exception->getMessage()}");
        }
    }
}