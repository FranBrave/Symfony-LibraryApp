<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BiblioType;
use App\Manager\BookManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{




    #[Route('/books', name: 'getbooks')]
    public function getAllBooks(EntityManagerInterface $doctrine)
    {
        $repository = $doctrine->getRepository(Book::class);
        $books = $repository->findAll();
        return $this->render('/books/listbook.html.twig', ['books' => $books]);
    }

    // #[Route('/book/insert')]
    // public function insertBook(EntityManagerInterface $doctrine)
    // {

    //     $inbook1 = new Book();
    //     $inbook1->setTitle('ADIOS, PEQUEÑO');
    //     $inbook1->setWriter('Máximo Huerta');
    //     $inbook1->setDescription('«Mi madre habría sido más feliz si yo no hubiera nacido.» Así arranca el desgarrador testimonio de un escritor enfrentado a la más dura de sus narraciones, la de su propia vida. Asaltado por los recuerdos mientras cuida a su madre enferma, el pasado se le presenta con vacíos que no logra llenar.

    //     A través de silencios y de un gran talento para la observación, el autor desnuda su intimidad y nos obsequia, con belleza y maestría, el retrato de un país y una época desde su propio universo familiar. Lo acompaña como confidente su vieja mascota, una perrita leal y encantadora.

    //     Descubrir por qué elegimos amar a quien no amamos exige una sinceridad implacable, y eso es lo que no falta en este hermoso relato de despedida. Adiós, pequeño es la reconstrucción emocionante de una infancia en la que todos, abuelos, padres e hijos, han callado demasiado.

    //     Cuando el pasado vuelve cargado de silencios.»');
    //     $inbook1->setGenre('Novela contemporánea');
    //     $inbook1->setCover('https://imagessl8.casadellibro.com/a/l/t7/68/9788408258568.jpg');
    //     $inbook1->setNumber(001);

    //     $inbook2 = new Book();
    //     $inbook2->setTitle('EL CASO ALASKA SANDERS');
    //     $inbook2->setWriter('Joël Dicker');
    //     $inbook2->setDescription('«Sé lo que has hecho». Este mensaje, encontrado en el bolsillo del pantalón de Alaska Sanders, cuyo cadáver apareció el 3 de abril de 1999 al borde del lago de Mount Pleasant, una pequeña localidad de New Hampshire, es la clave de la nueva y apasionante investigación que, once años después de poner entre rejas a sus presuntos culpables, vuelve a reunir al escritor Marcus Goldman y al sargento Perry Gahalowood. En esta Ocasión contarán con la inestimable ayuda de una joven agente de policía, Lauren Donovan, empeñada en resolver la trama de secretos que se esconde tras el caso. A medida que vayan descubriendo quién era realmente Alaska Sanders, irán resurgiendo también los fantasmas del pasado y, entre ellos, especialmente el de Harry Quebert.

    //     Una nueva intriga literariamente adictiva, con la estructura en varios tiempos, las vueltas de tuerca y el ritmo trepidante que son el sello inconfundible de Joël Dicker, «un fenómeno planetario» (Babelia).»');
    //     $inbook2->setGenre('Novela negra');
    //     $inbook2->setCover('https://imagessl7.casadellibro.com/a/l/t7/27/9788420462127.jpg');
    //     $inbook2->setNumber(002);


    //     $inbook3 = new Book();
    //     $inbook3->setTitle('ANIQUILACION');
    //     $inbook3->setWriter('Michel Houellebecq');
    //     $inbook3->setDescription('«Año 2027. Francia se prepara para unas elecciones presidenciales que es muy posible que gane una estrella de la televisión. El hombre fuerte detrás de esa candidatura es el actual ministro de Economía y Finanzas, Bruno Juge, para quien trabaja como asesor Paul Raison, el protagonista de la novela, un hombre taciturno y descreído.

    //     De pronto, en internet empiezan a aparecer extraños vídeos amenazantes –en uno de los cuales se guillotina al ministro Juge– con unos enigmáticos símbolos geométricos. Y la violencia pasa del mundo virtual al real: la explosión de un carguero en A Coruña, un atentado contra un banco de semen en Dinamarca y el sangriento ataque a una embarcación de migrantes en las costas mallorquinas. ¿Quién está detrás de estos hechos? ¿Grupos antiglobalización? ¿Fundamentalistas? ¿Acaso satanistas?

    //     Mientras Paul Raison indaga lo que está sucediendo, su relación matrimonial se descompone y su padre, espía jubilado de la DGSI, sufre un infarto cerebral y queda paralizado. El hecho propicia el reencuentro de Paul con sus hermanos: una hermana católica y simpatizante de la ultraderecha casada con un notario en paro, y un hermano restaurador de tapices casado con una periodista de segunda fila amargada y de colmillo retorcido. Y además Paul deberá enfrentar una crisis personal al serle diagnosticada una grave enfermedad...

    //     Houellebecq orquesta una ambiciosa novela total que es muchas cosas a la vez: un thriller con flecos esotéricos, una obra de crítica política, un descarnado retrato familiar y también una narración íntima y existencial sobre el dolor, la muerte y el amor, que acaso sea lo único que puede redimirnos y salvarnos.

    //     Una novela provocadora y apocalíptica que, como suele ser habitual en Houellebecq, deslumbrará o escandalizará. Lo que es seguro es que no dejará a nadie indiferente, porque el autor tiene la inusual virtud de sacudir conciencias.»');
    //     $inbook3->setGenre('Novela contemporánea');
    //     $inbook3->setCover('https://imagessl9.casadellibro.com/a/l/t7/19/9788433981219.jpg');
    //     $inbook3->setNumber(003);

    //     $doctrine->persist($inbook1);
    //     $doctrine->persist($inbook2);
    //     $doctrine->persist($inbook3);
    //     $doctrine->flush();
    //     return new Response('Libro insertado correctamente');
    // }

    #[Route('/book/add', name: 'addbook')]
    #[IsGranted('ROLE_ADMIN')]
    public function newBook(EntityManagerInterface $doctrine, Request $request, BookManager $manager)
    {
        $form = $this->createForm(BiblioType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();

            // $imageFile = $form->get('imageFile')->getData();


            // if ($imageFile) {

            //     $imageName = $manager->uploadImage($imageFile, $this->getParameter('kernel.project_dir') . '/public/images');
            //     $book->setImage('/images/' . $imageName);
            // }
            $doctrine->persist($book);
            $doctrine->flush();
            $this->addFlash("success", "Libro insertado correctamente");
            return $this->redirectToRoute("getbooks");
        }
        return $this->renderForm("books/newbookform.html.twig", ["biblioForm" => $form]);
    }


    #[Route('/book/edit/{id}', name: 'editbook')]
    #[IsGranted('ROLE_ADMIN')]
    public function editBook(EntityManagerInterface $doctrine, Request $request, $id)
    {
        $repository = $doctrine->getRepository(Book::class);
        $books = $repository->find($id);
        $form = $this->createForm(BiblioType::class, $books);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            $doctrine->persist($book);
            $doctrine->flush();
            $this->addFlash("success", "Libro editado correctamente");

            return $this->redirectToRoute("getbooks");
        }
        return $this->renderForm("books/newbookform.html.twig", ["biblioForm" => $form]);
    }

    #[Route('/book/{id}', name: 'getonebook')]
    public function getBook($id, EntityManagerInterface $doctrine)
    {
        $repository = $doctrine->getRepository(Book::class);
        $books = $repository->find($id);
        return $this->render('/books/detailbook.html.twig', ['book' => $books]);
    }


    #[Route('/book/remove/{id}', name: 'removebook')]
    #[IsGranted('ROLE_ADMIN')]
    public function removeBook($id, EntityManagerInterface $doctrine)
    {
        $repository = $doctrine->getRepository(Book::class);
        $book = $repository->find($id);

        $doctrine->remove($book);
        $doctrine->flush();

        $this->addFlash('success', 'Libro borrado correctamente');
        return $this->redirectToRoute('getbooks');
    }
}
