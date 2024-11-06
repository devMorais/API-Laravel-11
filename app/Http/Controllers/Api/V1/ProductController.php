<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\ProductStoreRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class ProductController extends Controller
{
    /**
     * Retrieve all products.
     *
     * Retorna uma lista JSON de todos os produtos disponíveis.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @api GET /api/v1/products
     * @example GET /api/v1/products
     * 
     * @response 200 [
     *     {"id": 1, "name": "Product 1", "description": "Description of Product 1", "price": 100.00},
     *     {"id": 2, "name": "Product 2", "description": "Description of Product 2", "price": 150.00}
     * ]
     */
    public function index(): JsonResponse
    {
        $products = Product::all();
        return response()->json($products, 200);
    }

    /**
     * Busca produtos pelo nome com base no termo de pesquisa.
     *
     * Se o parâmetro 'q' estiver presente na requisição, realiza a busca e retorna os produtos correspondentes.
     * Caso contrário, retorna uma lista vazia.
     *
     * @param Request $request Requisição contendo o termo de busca 'q'.
     * @return JsonResponse Lista de produtos encontrados ou vazia.
     */
    public function search(Request $request)
    {
        if ($request->has('q')) {
            $products = Product::where('name', 'like', '%' . $request->q . '%')->get();
            return response()->json($products, 200);
        }

        return response()->json([], 200);
    }

    /**
     * Armazena um novo produto no banco de dados.
     * Esse método cria uma nova instância de produto com os dados fornecidos
     * na requisição e salva o produto na base de dados.
     *
     * @param ProductStoreRequest $request Requisição validada contendo os dados do produto a ser criado.
     * - `name` (string): Nome do produto.
     * - `price` (float): Preço do produto.
     * - `image_url` (string): URL da imagem do produto.
     *
     * @return JsonResponse Retorna uma resposta JSON contendo os dados do produto criado e um código de status HTTP 201.
     *
     * @throws \Illuminate\Database\QueryException Se ocorrer um erro ao salvar no banco de dados.
     *
     * Exemplo de uso:
     * ```
     * POST /api/products
     * {
     *   "name": "Produto X",
     *   "price": 29.99,
     *   "image_url": "https://example.com/image.png"
     * }
     * ```
     */
    public function store(ProductStoreRequest $request): JsonResponse
    {
        // Cria uma nova instância de produto com os dados da requisição
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->image_url = $request->image_url;

        // Salva o produto no banco de dados
        $product->save();

        // Retorna uma resposta JSON com os dados do produto e status 201 (Created)
        return response()->json($product, 201);
    }

    /**
     * Exibe os detalhes de um produto específico.
     *
     * Este método localiza um produto pelo ID informado e retorna seus dados em formato JSON.
     * Em caso de sucesso, retorna um status HTTP 200.
     *
     * @param int $id ID do produto a ser exibido.
     * @return JsonResponse Resposta JSON contendo os detalhes do produto.
     * @throws ModelNotFoundException Caso o produto não seja encontrado.
     */
    public function show($id): JsonResponse
    {
        $product = Product::findOrFail($id);
        return response()->json($product, 200);
    }

    /**
     * Atualiza um produto específico no banco de dados.
     *
     * @param  ProductStoreRequest  $request  Objeto contendo os dados validados para atualização.
     * @param  int  $id  ID do produto a ser atualizado.
     * @return JsonResponse  Retorna uma resposta JSON com a mensagem de sucesso e o código HTTP 200.
     *
     * Este método localiza o produto pelo ID informado, atualiza as propriedades
     * de acordo com os dados enviados na requisição e salva as alterações.
     * Retorna uma mensagem de confirmação caso a atualização ocorra com sucesso.
     */
    public function update(ProductStoreRequest $request, $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->image_url = $request->image_url;

        // Atualiza o produto no banco de dados
        $product->save();

        return response()->json(['message' => 'Atualizado com sucesso'], 200);
    }

    /**
     * Remove o produto especificado pelo ID.
     *
     * @param int $id ID do produto a ser removido.
     * @return JsonResponse Confirmação de exclusão com mensagem de sucesso.
     * @throws ModelNotFoundException Caso o produto não seja encontrado.
     */
    public function destroy($id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Deletado com sucesso'], 200);
    }
}
