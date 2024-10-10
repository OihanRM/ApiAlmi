<?php

namespace App\Controller;
use App\Entity\Curso;
use App\Entity\Asignatura;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\CursoRepository;


class AlmiController extends AbstractController
{
    #[Route('/ws/almi', name: 'app_almi')]
    public function index(CursoRepository $cursoRepository): Response
    {
        return $this->render('almi/index.html.twig', [
            'controller_name' => 'AlmiController',
        ]);
    }
    #[Route('/ws/almi/curso/add', name: 'app_almi_curso', methods:['POST'])]
    public function addCurso(CursoRepository $cursoRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if(empty($data)){
            return new JsonResponse(['status' => 'Invalid data, Empty data'], Response::HTTP_BAD_REQUEST);
        }
        $curso = new Curso($data['nombre'], $data['descripcion']);
        $cursoRepository->add($curso, true);
        return new JsonResponse(['status' => 'Curso created!'], Response::HTTP_CREATED);
    }








    #[Route('/ws/almi/cursos', name: 'app_cursos', methods: ['GET'])]
    public function getCursos(CursoRepository $cursoRepository): Response
    {
        return $this->convertToJson($cursoRepository->findAll());
    }

    #[Route('/ws/almi/curso/{id}', name: 'app_curso_id', methods: ['GET'])]
    public function show(CursoRepository $cursoRepository, $id): Response
    {
        return $this->convertToJson($cursoRepository->find($id));
    }

    private function convertToJson($data):JsonResponse
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $normalized = $serializer->normalize($data,null,array(DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'));
        $jsonContent = $serializer->serialize($normalized, 'json');
        return JsonResponse::fromJsonString($jsonContent, 200);
    }
    
}
