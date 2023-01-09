<?php
namespace App\Controller;
use App\Entity\Utilisateur;
use App\Entity\Pieces;
use App\Entity\Operation;
use App\Form\UtilisateurType;
use App\Form\PiecesType; 
use App\Form\OperationType; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
class PersonnelController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
  
    /**
     * @Route("/interfaceuser{s}/{idc}",name="interfaceu")
     */
    public function interfaceuser(PiecesRepository $pieces ,Request $req , $s , $idc , UtilisateurRepository $users )
    {   
        $all=$users->findAll();
        $findn = $users->find($idc);
        $name = $findn->getName();
        $p = $pieces->findAll();
        return $this->render("user/interfaceuser.html.twig",['pieces'=>$p,'s'=>$name,'idc'=>$idc]) ;
    }
    /**
     
     * @Route("/addp/{s}/addpiece{idc}",name="addp")
     */
    public function addp(Request $req , Request $r, UtilisateurRepository $users ,$s , piecesRepository $piecess , $idc  )
    {

        $all=$users->findAll();
        $findn = $users->find($idc);
        $name = $findn->getName();
        $p = $piecess->findAll();
        $piece = new Pieces();
        $piece->setDate(new \DateTime());
        $formp = $this-> createForm(PiecesType::class,$piece);  
        $formp->add('drawingnumber',ChoiceType::class,array( 'choices' =>[ 'Empty'=>null , 'bg'=>'bg', 'assm'=>'assm' ]));
        $formp->add('utilisateur',ChoiceType::class,[ 'choices'=>[
            $name=>$name,],
        ]);
           
           $formp->add('typekeylist',ChoiceType::class,[ 'choices'=>[
               'selectionner un choix'=>null,
               '3'=>3,
               '10'=>10,
               '15'=>15,
               '16'=>16,],
           ]);
           $formp ->add('prductnumber',ChoiceType::class,[ 'choices'=>[
              'selectionner un choix'=>null,
               "335"=>335 ,
               "415"=>415 ,],
            ]);
            $formp -> handleRequest($req) ;
            $op = new Operation();
            $fo = $this->createForm(OperationType::class,$op); 
            $fo->add('boucle');
            $fo ->handleRequest($r) ;
        if($formp->isSubmitted() && $formp->isValid() )
         {
          
            $test= $op->getBoucle();
            $em= $this->getDoctrine()->getManager();
            $piece->setUtilisateur($name);
            $em->persist($piece);     
            $k =1 ;
            do {
              $k++;
              $em->flush();
              $em->detach($piece);
              $em= $this->getDoctrine()->getManager();
              $piece->setUtilisateur($name);
              $em->persist($piece); 
            } while ($k <= $test);  
           
         return $this->redirectToroute('interfaceu',['s'=>$name,'idc'=>$idc,'pieces'=>$p  ]);       
         }
        return $this->render("user/ajoutp.html.twig",['f'=>$formp->createView(), 'fo'=>$fo->createView() ,'s'=>$name,'fb'=>$fo->createView(),'idc'=>$idc]); 
    }
/**
 * @Route("/updatepu{id}/{s}/{idc}",name="updatepu")
 */
public function updatepu($id,$s,$idc,Request $req , UtilisateurRepository $users, PiecesRepository $pieces)
{
       $all = $users->findAll();
       $findn = $users->find($idc);
       $name = $findn->getName();
        $allp = $pieces->findAll();
        $p = new Pieces();
        $p = $this->getDoctrine()->getRepository(Pieces::class)->find($id);
        $fpiece = $this->createForm(PiecesType::class,$p);
        $fpiece->add('drawingnumber',ChoiceType::class,array( 'choices' =>[ 'Empty'=>null , 'bg'=>'bg', 'assm'=>'assm' ]));
          $fpiece->add('utilisateur',TextType::class,array(
           'disabled'=>true,));
          $arr2 = array();
          foreach ($allp as $i)
          {   
          $arr2[$i->getPrductnumber()]=$i->getPrductnumber()   ;  }
          $fpiece ->add('prductnumber',ChoiceType::class,array(
            'choices'=>$arr2 ,
        ));
            $fpiece->add('typekeylist',ChoiceType::class,[ 'choices'=>[     
                '3'=>3,
                '10'=>10,
                '15'=>15,
                '16'=>16,],
            ]);
        $fpiece -> handleRequest($req) ;    
        if($fpiece->isSubmitted() && $fpiece->isValid() )
        {
            $em = $this->getDoctrine()->getManager(); 
            $em->persist($p);
            $em->flush();
            return $this->redirectToroute('interfaceu',['s'=>$name,'idc'=>$idc,'pieces'=>$p]);
        }
        return $this->render('user/modifpu.html.twig',['s'=>$name,'idc'=>$idc,'fp'=>$fpiece->createview()]);
}
    /**
     * @Route("/l",name="logoutu")
     */
    public function logout()
    {
        return $this->redirectToRoute('app_home');
    }
    
     /**
     * @Route("/chercher{s}/{idc}",name="chercher")
     */
    public function chercher( Request $r , piecesRepository $pieces , $s ,$idc , UtilisateurRepository $users)
    {
    $findn = $users->find($idc);
    $name = $findn->getName();
    $op = new Operation();
    $search = $this-> createForm(OperationType::class,$op); 
    $search->add('searchid');
    $search ->handleRequest($r) ;  
    if( $search->isSubmitted() && $search->isValid() )

    {
        
        $res = $pieces->find($op->getSearchid());
        return  $this->render('user/resultatch.html.twig',['res'=>$res,'s'=>$name,'idc'=>$idc]);
    }

    return  $this->render('user/search.html.twig',['f'=>$search->createView(),'s'=>$s,'idc'=>$idc]);

    }

    /**
     * @Route("/infos{idc}{s}",name="infos")
     */
     public function infosc($idc , $s,  UtilisateurRepository $users) 
     {
        $infos = $users->find($idc);
        $name =  $infos->getName();
        $infos = $users->findOneByName($name);
        return  $this->render('user/infos.html.twig',['infos'=>$infos,'s'=>$s,'idc'=>$idc]);
     }   

    }