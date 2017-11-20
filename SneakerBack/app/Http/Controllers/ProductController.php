<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Product; //-> eloquent
use App\Product_Image;

class ProductController extends Controller
{
    function GetAllProduct(){
        $productList = DB::select(
        'select p.id, p.name, p.unit_price, pi.image 
        from products p
        join product__images pi on p.id = pi.product_id
        where pi.default_image = 1'
    );
    // $pList = App/product::all(); --> eloquent ORM
    // insert eloquent
    // $obj = new Product;
    // $obj->name = "New Name";
    // $obj->save();

    // $pList = DB::table('products as p') --> query builder (syntax laravel)
    // ->join('product__images as pi', 'pi.product_id', '=', 'p.id')
    // ->where('pi.default_image', '=', true)
    // ->select('p.name', 'p.unit_price', 'pi.image')
    // ->get()
        return response()->json($productList,200);
    }

    function GetProductById(Request $request){
        
        DB::beginTransaction();
        try{
            // $this->validate($req, [
            //     'product_id' => 'required'
            // ]);
            $productId = $request->input('product_id');
            
            // $productList = DB::select(
            //     'select p.id, p.name, p.unit_price, p.description, pi.image,
            //         pi.default, pd.size, pd.stock
            //         from products p
            //         left join product_images pi on p.id = pi.product_id
            //         left join product_details pd on p.id = pd.product_id
            //         where p.id = ' . $productId . ' ');

            $product = DB::selectOne(DB::raw('select id, name, unit_price, 
                description from products 
                where id = ? ') , [$productId]);

            if(empty($product)){
                return response()->json(['message' => 'product not found'], 404);
            }

            $productDetails = DB::select(DB::raw('select id product_details_id,
                size, stock from product__details 
                where product_id = :pId'), ['pId' => $productId]);
            $productImages = DB::select('select *
                from product__images 
                where product_id = ' . $productId . ' ');
            $product->product_details = $productDetails;
            $product->product_images = $productImages;
            DB::commit();
            return response()->json($product, 200); 
        }
        catch(\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Failed to create user, exception:' + $e], 500); 
        }
    }

    function SaveProductImage(Request $request){
        DB::beginTransaction();
        try{
            $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'product_id' => 'required'
            ]);

            $productId = $request->input('product_id');
            $image = $request->file('image');

            $file_name = time() . '.' . $image->getClientOriginalExtension(); 
            $file_path = '/img/product/' . $productId;
            $file_destination = public_path($file_path);

            $productImage = new Product_Image;
            $productImage->product_id = $productId;
            $productImage->image = $file_path . "/" . $file_name;
            $productImage->default_image = false;
            $productImage->save();

            DB::commit();
    
            $image->move($file_destination, $file_name); //--> move('destination', 'file name')
            return "Successful";
        }
        catch(\Exception $e){
            DB::rollback();
        }
    }
}
