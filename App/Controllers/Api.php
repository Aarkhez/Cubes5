<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Models\Cities;
use \Core\View;
use Exception;

/**
 * @OA\Info(title="API de Mon Application", version="1.0.0")
 */

class Api extends \Core\Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Articles"},
     *     summary="Récupère la liste des articles",
     *     description="Récupère une liste des articles disponibles en fonction du critère de tri spécifié.",
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Critère de tri pour les articles, pour trier par vues ou pour trier par date de publication.",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"views", "date"},
     *             example="views"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Rechercher des articles par nom.",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="Jeu"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La requête a été effectuée avec succès.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Article 1"),
     *                 @OA\Property(property="description", type="string", example="Description de l'article"),
     *                 @OA\Property(property="published_date", type="string", format="date", example="2023-06-21"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="views", type="integer", example=100),
     *                 @OA\Property(property="picture", type="string", example="path/to/picture.jpg")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Requête incorrecte."
     *     )
     * )
     * @throws Exception
     */

    public function ProductsAction()
    {
        $query = $_GET['sort'];

        $articles = Articles::getAll($query);

        header('Content-Type: application/json');
        echo json_encode($articles);
    }

    /**
     * @OA\Get(
     *     path="/api/cities",
     *     tags={"Villes"},
     *     summary="Recherche dans la liste des villes",
     *     description="Effectue une recherche dans la liste des villes en fonction du critère de recherche spécifié.",
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Critère de recherche pour les villes. Rechercher les villes dont le nom commence par cette chaîne.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="Par"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des villes correspondant à la recherche.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="ville_id", type="integer", example=1),
     *                 @OA\Property(property="ville_nom_reel", type="string", example="Paris")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Requête incorrecte."
     *     )
     * )
     * @throws Exception
     */

    public function CitiesAction(){

        $cities = Cities::search($_GET['query']);

        header('Content-Type: application/json');
        echo json_encode($cities);
    }
}
