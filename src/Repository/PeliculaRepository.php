<?php

namespace App\Repository;

use App\Entity\Pelicula;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pelicula|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pelicula|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pelicula[]    findAll()
 * @method Pelicula[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeliculaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pelicula::class);
    }

    public function guardar($nombre, $genero, $descripcion)
    {
        $pelicula = new Pelicula();
        $pelicula->setNombre($nombre)
            ->setGenero($genero)
            ->setDescripcion($descripcion);
        $this->manager->persist($pelicula);
        $this->manager->flush();
    }

    public function eliminar(Pelicula $pelicula)
    {
        $this->manager->remove($pelicula);
        $this->manager->flush();
    }

    public function actualizar(Pelicula $pelicula)
    {
        $this->manager->persist($pelicula);
        $this->manager->flush();
    }



}
