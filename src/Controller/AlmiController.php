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
use App\Repository\AsignaturaRepository;



class AlmiController extends AbstractController
{
    // CURSOS 
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
        $cursoRepository->addCurso($curso, true);
        return new JsonResponse(['status' => 'Curso created!'.$data['nombre']], Response::HTTP_CREATED);
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

    #[Route('/ws/cursos/update/{id}' , name: 'cursos_update', methods: ['PUT'])]
    public function update(CursoRepository $cursoRepository, Request $request, $id): Response
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new NotFoundHttpException('Faltan Parametros'.$request);
        }
        $curso = $cursoRepository-> findOneBy(['id' => $id]);
        if ($curso === null) {
            throw new NotFoundHttpException("Curso no encontrado");
        }
        // si hay nombre lo cambio
        if (isset($data['nombre'])) {$curso->setNombre($data['nombre']);}
        if (isset($data['descripcion'])) {$curso->setDescripcion($data['descripcion']);}
        $cursoRepository->addCurso($curso);
        return new JsonResponse(['status' => 'Curso actualizado!'], Response::HTTP_OK);
    }
    #[Route('/ws/cursos/delete/{id}' , name: 'app_cursos_delete', methods: ['DELETE'])]
    public function delete(CursoRepository $cursoRepository, $id): Response
    {
        $curso = $cursoRepository-> findOneBy(['id' => $id]);
        if ($curso === null) {
            throw new NotFoundHttpException("Curso no encontrado");
        }
        $cursoRepository->deleteCurso($curso);
        return new JsonResponse(['status' => 'Curso borrado!'], Response::HTTP_OK);
    }

    // ASIGNATURAS
    #[Route('/ws/almi/asignatura/add', name: 'app_almi_asignatura', methods:['POST'])]
    public function addAsignatura(AsignaturaRepository $asignaturaRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if(empty($data)){
            return new JsonResponse(['status' => 'Invalid data, Empty data'], Response::HTTP_BAD_REQUEST);
        }
        $asignatura = new Asignatura($data['nombre'], $data['descripcion'], $data['horas'], $data['profesor']);
        $asignaturaRepository->addAsignatura($asignatura, true);
        return new JsonResponse(['status' => 'Asignatura created!'." ".$data['nombre']], Response::HTTP_CREATED);
        
    }
    #[Route('/ws/almi/asignaturas', name: 'app_asignaturas', methods: ['GET'])]
    public function getAsignaturas(AsignaturaRepository $asignaturaRepository): Response
    {
        return $this->convertToJson($asignaturaRepository->findAll());
    }

    #[Route('/ws/almi/asignatura/{id}', name: 'app_asignatura_id', methods: ['GET'])]
    public function showasignatura(AsignaturaRepository $asignaturaRepository, $id): Response
    {
        return $this->convertToJson($asignaturaRepository->find($id));
    }

    #[Route('/ws/almi/curso/{id}/asignaturas', name: 'app_curso_asignaturas', methods: ['GET'])]
    public function getAsignaturasDeCurso(CursoRepository $cursoRepository, $id): JsonResponse
    {
        $curso = $cursoRepository->find($id);

        if (!$curso) {
            return new JsonResponse(['status' => 'Curso no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $asignaturas = $curso->getAsignaturas();

        $asignaturasArray = [];
        foreach ($asignaturas as $asignatura) {
            $asignaturasArray[] = [
                'id' => $asignatura->getId(),
                'nombre' => $asignatura->getNombre(),
                'descripcion' => $asignatura->getDescripcion(),
                'horas' => $asignatura->getHoras(),
                'profesor' => $asignatura->getProfesor()
            ];
        }
        return new JsonResponse($asignaturasArray, Response::HTTP_OK);
    }

    #[Route('/ws/almi/asignatura/{id}/edit', name: 'app_edit_asignatura', methods: ['PUT'])]
    public function editAsignatura(AsignaturaRepository $asignaturaRepository, Request $request, $id): JsonResponse
    {
        $asignatura = $asignaturaRepository->find($id);

        if (!$asignatura) {
            return new JsonResponse(['status' => 'Asignatura no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        // Verifica si los campos existen en los datos y actualiza solo los que estén presentes
        if (isset($data['nombre'])) {
            $asignatura->setNombre($data['nombre']);
        }
        if (isset($data['descripcion'])) {
            $asignatura->setDescripcion($data['descripcion']);
        }
        if (isset($data['horas'])) {
            $asignatura->setHoras($data['horas']);
        }
        if (isset($data['profesor'])) {
            $asignatura->setProfesor($data['profesor']);
        }

        $asignaturaRepository->addAsignatura($asignatura, true);

        return new JsonResponse(['status' => 'Asignatura actualizada'], Response::HTTP_OK);
    }

    #[Route('/ws/almi/asignatura/{id}/delete', name: 'app_delete_asignatura', methods: ['DELETE'])]
    public function deleteAsignatura(AsignaturaRepository $asignaturaRepository, $id): JsonResponse
    {
        $asignatura = $asignaturaRepository->find($id);

        if (!$asignatura) {
            return new JsonResponse(['status' => 'Asignatura no encontrada'], Response::HTTP_NOT_FOUND);
        }

        $asignaturaRepository->removeAsignatura($asignatura, true);

        return new JsonResponse(['status' => 'Asignatura eliminada'], Response::HTTP_OK);
    }




    // ENLACES
    #[Route('/ws/almi/asignatura/assign/{curso_id}/{asignatura_id}', name: 'app_asignatura_assign', methods:['POST'])]
    public function assignAsignaturaToCurso(AsignaturaRepository $asignaturaRepository, CursoRepository $cursoRepository, int $curso_id, int $asignatura_id): JsonResponse
    {
        $curso = $cursoRepository->find($curso_id);
        if ($curso === null) {
            return new JsonResponse(['status' => 'Curso no encontrado'], Response::HTTP_NOT_FOUND);
        }
        $asignatura = $asignaturaRepository->find($asignatura_id);
        if ($asignatura === null) {
            return new JsonResponse(['status' => 'Asignatura no encontrada'], Response::HTTP_NOT_FOUND);
        }
        $curso->addAsignatura($asignatura);
        $cursoRepository->addCurso($curso); 
        return new JsonResponse(['status' => 'Asignatura asignada al curso!'], Response::HTTP_OK);
    }
















    private function convertToJson($data):JsonResponse
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(null, null, null, null, null, null, [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ])];
        $serializer = new Serializer($normalizers, $encoders);
        $normalized = $serializer->normalize($data,null,array(DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'));
        $jsonContent = $serializer->serialize($normalized, 'json');
        return JsonResponse::fromJsonString($jsonContent, 200);
    }

}
