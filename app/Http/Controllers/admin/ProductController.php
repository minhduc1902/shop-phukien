<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Origin;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function index(Request $request) {
        $products = Product::latest('id')->with('product_images');
        if($request->get('keyword') != "") {
            $products = $products->where('title', 'like', '%'.$request->keyword.'%');
        }
        $products = $products->paginate(10);
        $data['products'] = $products;
        return view('admin.products.list', $data);
    }

    public function create() {
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $origins = Origin::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['origins'] = $origins;
        return view('admin.products.create', $data);
    }

    public function store(Request $request) {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if(!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()) {
            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->origin_id = $request->origin;
            $product->is_featured = $request->is_featured;
            $product->sku = $request->sku;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;

            $product->save();

            if(!empty($request->image_array)) {
                foreach($request->image_array as $temp_image_id) {

                    $tempImageInfo = TempImages::find($temp_image_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray); //like jpg, gif, png

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    //Generate Product Thumbnail

                    //Large Image
                    $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                    $destPath = public_path().'/uploads/product/large/'.$imageName;
                    $image = Image::make($sourcePath);
                    $image->resize(1400, null, function($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save($destPath);

                    //Small Image
                    $destPath = public_path().'/uploads/product/small/'.$imageName;
                    $image = Image::make($sourcePath);
                    $image->fit(300, 300);
                    $image->save($destPath);
                }
            }

            session()->flash('success', 'Sản phẩm được tạo thành công.');

            return response()->json([
                'status' => true,
                'message' => 'Sản phẩm được tạo thành công.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request) {
        $product = Product::find($id);

        if(empty($product)) {
            return redirect()->route('products.index')->with('error', 'Không tìm thấy sản phẩm');
        }

        //Fetch Product Images
        $productImages = ProductImage::where('product_id', $product->id)->get();

        $subCategories = SubCategory::where('category_id', $product->category_id)->get();

        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $origins = Origin::orderBy('name', 'ASC')->get();
        $data['product'] = $product;
        $data['subCategories'] = $subCategories;
        $data['categories'] = $categories;
        $data['productImages'] = $productImages;
        $data['origins'] = $origins;
        return view('admin.products.edit', $data);
    }

    public function update($id, Request $request) {
        $product = Product::find($id);

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if(!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);
        if($validator->passes()) {
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->origin_id = $request->origin;
            $product->is_featured = $request->is_featured;
            $product->sku = $request->sku;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->save();

            //Save Gallery Pics

            session()->flash('success', 'Sản phẩm được cập nhật thành công.');

            return response()->json([
                'status' => true,
                'message' => 'Sản phẩm được cập nhật thành công.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function delete($id, Request $request) {
        $product = Product::find($id);

        if(empty($product)) {
            session()->flash('error', 'Không tìm thấy sản phẩm');

            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $productImages = ProductImage::where('product_id', $id)->get();

        if(!empty($productImages)) {
            foreach($productImages as $productImage) {
                File::delete(public_path('uploads/product/large/'.$productImage->image));
                File::delete(public_path('uploads/product/small/'.$productImage->image));
            }
            ProductImage::where('id', $id)->delete();
        }
        $product->delete();

        session()->flash('success', 'Sản phẩm được xóa thành công');

        return response()->json([
            'status' => true,
            'message' => 'Sản phẩm được xóa thành công.'
        ]);
    }

    public function getProducts(Request $request) {
        $tempProduct = [];
        if($request->term != "") {
            $products = Product::where('title', 'like', '%'.$request->term.'%')->get();

            if($products != null) {
                foreach($products as $product) {
                    $tempProduct[] = array('id' => $product->id, 'text' => $product->title);
                }
            }
        }

        return response()->json([
            'tags' => $tempProduct,
            'status' => true
        ]);
    }
}
