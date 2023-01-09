<?php
namespace App\Controller;
use App\Entity\Admin;
use App\Entity\Utilisateur;
use App\Entity\Pieces;
use App\Entity\Operation;
use App\Form\AdminType; 
use App\Form\UtilisateurType;
use App\Form\PiecesType; 
use App\Form\OperationType; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdminRepository; 
use App\Repository\UtilisateurRepository; 
use App\Repository\PiecesRepository; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType; 
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Form;
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('home/principale.html.twig');
    }
     /**
     * @Route("/loginuser",name="loginuser")
     */
    public function loginuser(Request $req  , UtilisateurRepository $users , PiecesRepository $pieces) 
    {
        $piece = new Pieces();
        $formr = $this-> createForm(PiecesType::class,$piece);
        $p = $pieces->findAll();
        $all = $users->findAll();
        $user = new Utilisateur();
        $formlu = $this-> createForm(UtilisateurType::class,$user);
        $formlu->add('password',PasswordType::class);
        $formlu->add('name');
        $formlu -> handleRequest($req) ;
        $s ="";
        if ($formlu->isSubmitted() && $formlu->isValid())
        {
            $test =  $users->findOneByName($user->getName()) ;
              if( ( empty($test) == false  ) && ($user->getPassword() == $test->getPassword() ))
              {
                $s = $test->getName();
                $id = $test->getId();                
                return $this->render("user/interfaceuser.html.twig",['pieces'=>$p,'s'=>$s,'idc'=>$id]) ;
              }else 
              $this->addFlash('er','Verifier votre Nom utilisateur \  Mot de passe');
        }   

          return $this->render("home/loginuser.html.twig",['flu'=>$formlu->createView()]);
    }
    /**
     * @Route("/loginadmin",name="loginadmin")
     */
    public function loginadmin( Request $req ,  AdminRepository $admins ,PiecesRepository $pieces ,UtilisateurRepository $users )
    { 
        $p = $pieces->findAll();
        $allad = $admins->findAll();
        $admin = new Admin();
        $form = $this-> createForm(AdminType::class,$admin);
        $form -> handleRequest($req); 
        if ($form->isSubmitted() && $form->isValid() )
        {
            $test =  $admins->findOneByName($admin->getName()) ;
         
              if( ( empty($test) == false  )&& ($admin->getPassword() == $test->getPassword() ))
              {
                $session = $admin->getName();
                $id = $test->getId(); 
                return $this->render("admin/pghome.html.twig",['pieces'=>$p,'s'=>$session,'idc'=>$id]) ;

            }else
                 $this->addFlash('e','Verifier votre Nom utilisateur \ Mot de passe');
            
        }
            
          return $this->render("home/loginadmin.html.twig",['form'=>$form->createView()]);
    }
}
