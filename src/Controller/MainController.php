<?php

namespace App\Controller;

use App\Repository\PeliculaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    private $repositorio;

    public function __construct(PeliculaRepository $repositorio)
    {
        $this->repositorio = $repositorio;
    }

    /**
     * @Route("pelicula", name="addPelicula", methods={"POST"} )
     */
    public function addPelicula(Request $peticion)
    {
        $contenido = $peticion->getContent();

        $datos = json_decode($contenido, true);

        $nombre = $datos['nombre'];
        $genero = $datos['genero'];
        $descripcio = $datos['descripcion'];

        if ( empty($nombre) || empty($genero) || empty($descripcio) ){
            throw new NotFoundHttpException('Error de datos');
        }else{
            $this->peliculaRepository->guardarPelicula($nombre, $genero, $descripcio);
            return new JsonResponse( ['status' => 'Operacion correcta'], Response::HTTP_CREATED);
        }
    }


    /**
     * @Route("pelicula/{id}", name="getPelicula", methods={"GET"} )
     */
    public function getPelicula(Request $peticion, $id)
    {
        $pelicula = $this->repositorio->findOneBy(['id' => $id]);

        $respuesta = [
            'id' => $pelicula->getId(),
            'nombre' => $pelicula->getNombre(),
            'genero' => $pelicula->getGenero(),
            'descripcion' => $pelicula->getDescripcion()];

        $respuesta_json = json_encode($respuesta);

        return new JsonResponse($respuesta_json, Response::HTTP_OK);
    }

    /**
     * @Route("peliculas", name="getAllPeliculas", methods={"GET"} )
     */
    public function getAllPeliculas(Request $peticion)
    {
        $peliculas = $this->repositorio->findAll();

        $respuesta = array();
        foreach ($peliculas as $pelicula){
            $peliculaParaEnviar = array(
                'id' => $pelicula->getId(),
                'nombre' => $pelicula->getNombre(),
                'genero' => $pelicula->getGenero(),
                'descripcion' => $pelicula->getDescripcion()
            );
            array_push($respuesta, $peliculaParaEnviar);
        }

        return new JsonResponse($respuesta, Response::HTTP_OK);

    }

    /**
     * @Route("pelicula/{id}", name="updatePelicula", methods={"PUT"} )
     */
    public function updatePelicula(Request $peticion, $id)
    {
        $contenido = $peticion->getContent();
        $datos = json_decode($contenido, true);

        $pelicula = $this->repositorio->findOneBy(['id' => $id]);

        empty( $datos['nombre'] ) ? true : $pelicula->setNombre( $datos['nombre'] );
        empty( $datos['genero'] ) ? true : $pelicula->setGenero( $datos['genero'] );
        empty( $datos['descripcion'] ) ? true : $pelicula->setDescripcion( $datos['descripcion'] );
        $this->repositorio->actualizar($pelicula);

        return new JsonResponse( ['status' => 'Actualizada'], Response::HTTP_CREATED);

    }

    /**
     * @Route("pelicula/{id}", name="deletePelicula", methods={"DELETE"} )
     */
    public function deletePelicula(Request $peticion, $id)
    {
        $pelicula = $this->repositorio->findOneBy(['id' => $id]);
        $this->repositorio->eliminar($pelicula);
        return new JsonResponse( ['status' => 'Eliminada'], Response::HTTP_CREATED);

    }

}
