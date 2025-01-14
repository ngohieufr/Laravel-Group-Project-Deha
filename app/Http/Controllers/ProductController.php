<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    protected $category;
    protected $product;

    /**
     * Constructor để gán middleware.
     */
    public function __construct(Category $category, Product $product)
    {
        $this->category = $category;
        $this->product = $product;

        // Middleware kiểm tra quyền
        $this->middleware('can:view,App\Models\Product')->only(['index', 'show']);
        $this->middleware('can:create,App\Models\Product')->only(['create', 'store']);
        $this->middleware('can:edit,product')->only(['edit', 'update']);
        $this->middleware('can:delete,product')->only(['destroy']);
    }

    /**
     * Hiển thị danh sách sản phẩm.
     */
    public function index(Request $request): View
    {
        // Lấy các tham số tìm kiếm
        $searchTerm = $request->input('search');
        $categoryName = $request->input('category');
        $minPrice = $request->input('minPrice');
        $maxPrice = $request->input('maxPrice');

        // Lấy danh sách danh mục
        $categories = $this->category->pluck('name', 'id');

        // Tìm kiếm sản phẩm
        $products = $this->product->searchByName($searchTerm)
            ->searchByCategory($categoryName)
            ->searchByPrice($minPrice, $maxPrice)
            ->paginate(5);

        return view('products.index', compact('products', 'categories'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Hiển thị form tạo sản phẩm.
     */
    public function create(): View
    {
        $categories = $this->category->all();
        return view('products.create', compact('categories'));
    }

    /**
     * Lưu sản phẩm mới.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }

        $this->product->create($input);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Hiển thị chi tiết sản phẩm.
     */
    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm.
     */
    public function edit(Product $product): View
    {
        $categories = $this->category->all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật sản phẩm.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        } else {
            unset($input['image']);
        }

        $product->update($input);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Xóa sản phẩm.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
