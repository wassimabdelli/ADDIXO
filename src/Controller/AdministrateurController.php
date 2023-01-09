<?php
namespace App\Controller;
use App\Entity\Admin;
use App\Entity\Pieces;
use App\Entity\Utilisateur;
use App\Entity\Operation;
use App\Form\AdminType; 
use App\Form\PiecesType;
use App\Form\UtilisateurType;
use App\Form\OperationType; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdminRepository; 
use App\Repository\PiecesRepository; 
use App\Repository\UtilisateurRepository; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Form;
use\Symfony\Component\Validator\Constraints\NotBlank;
use\Symfony\Component\Validator\Constraints\NotNull;
class AdministrateurController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    
    /**
     * @Route("/interfaceadmin/{idc}/{s}",name="inter")
     */
    public function iterfaceadmin($idc , $s ,Request $req , PiecesRepository $pieces , AdminRepository $admin)
    {
        $findn = $admin->find($idc);
        $nom = $findn->getName();
        $p = $pieces->findAll();
        return $this->render("admin/pghome.html.twig",['pieces'=>$p,'s'=>$nom,'idc'=>$idc]) ;
    }
     /**
     * @Route("/addpadmin/{s}/{idc}",name="addpadmin")
     */
    public function addpadmin( $s, Request $req , Request $r , UtilisateurRepository $users , piecesRepository $pieces , $idc , AdminRepository $admin )
    {
        $findn = $admin->find($idc);
        $nom = $findn->getName();
        $all=$users->findAll();
        $p = $pieces->findAll();
        $piece = new Pieces();
        $piece->setDate(new \DateTime());
        $formp = $this-> createForm(PiecesType::class,$piece);  
        $formp->add('drawingnumber',ChoiceType::class,array( 'choices' =>[ 'Empty'=>null , 'bg'=>'bg', 'assm'=>'assm' ]));
        $formp->add('utilisateur',TextType::class,array(
        'disabled'=>true,
        'empty_data'=>$s,
       ));     
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
            $piece->setUtilisateur($s);
            $em->persist($piece);     
            $k =1 ;
            do {
              $k++;
              $em->flush();
              $em->detach($piece);
              $em= $this->getDoctrine()->getManager();
              $piece->setUtilisateur($s);
              $em->persist($piece); 
            } while ($k <= $test);  
           
            return $this->redirectToroute('inter',['pieces'=>$p,'s'=>$nom,'idc'=>$idc]);
         }
        return $this->render("admin/ajoutp.html.twig",['f'=>$formp->createView(), 'fo'=>$fo->createView(),'s'=>$nom,'idc'=>$idc]); 
    }
    /**
     * @Route("/users/{s}/{idc}",name="users")
     */
    public function users(UtilisateurRepository $users,$idc,$s,AdminRepository $admin)
    {
        $findn = $admin->find($idc);
        $nom = $findn->getName();
        $u = $users->findAll();
        return $this->render("admin/users.html.twig",['u'=>$u,'idc'=>$idc,'s'=>$nom]) ;
    }
    /**
     * @Route("/updateu/{id}/{idc}/{s}",name="updateu")
     */
    public function updateu($id , $idc , $s , Request $req , AdminRepository $admin)
    {
        $findn = $admin->find($idc);
        $n = $findn->getName();  
        $u = new Utilisateur();
        $u = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
        $fname = $u->getFname();
        $lname = $u->getLname();
        $nom  =  $u->getName();
        $mdp =  $u->getPassword();
        $email =  $u->getEmail();
        $desc =  $u->getDescription();
        $v =  $u->getVille();
        $form = $this->createForm(UtilisateurType::class,$u);
        $form->add('fname',TextType::class , [
           'empty_data'=>$fname
        ]
        
        );
        $form->add('lname' ,TextType::class , [
            'empty_data'=>$fname
         ]
         
         );
        $form->add('name' ,TextType::class , [
            'empty_data'=>$fname
         ]
         
         );
        $form->add('password' ,TextType::class  , [
            'empty_data'=>$fname
         ]
         
         );
        $form->add('email');
        $form->add('description');
        $form->add('ville');
        $form -> handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()    )
        {
         
            $em = $this->getDoctrine()->getManager();
            $em -> flush();
            return $this->redirectToRoute('users',['s'=>$n,'idc'=>$idc]);
       
           
       
        }
           
        return $this->render('admin/modifu.html.twig',['f'=>$form->createview(),'s'=>$n,'idc'=>$idc, 'fn'=>$fname,'ln'=>$lname,'n'=>$nom,'mdp'=>$mdp,'mail'=>$email,'desc'=>$desc,'v'=>$v]);
    }
    /**
     * @Route("/suppru/{id}/{idc}/{s}",name="suppru")
     */
    public function suppru($id , $idc , $s , Request $req , AdminRepository $admin )
    {
        $findn = $admin->find($idc);
        $nom = $findn->getName();
        $u = new Utilisateur(); 
        $u = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
        $forms = $this->createForm(UtilisateurType::class,$u);
        $forms->add('fname' ,TextType::class,array(
            'disabled'=>true,
            'empty_data'=>$s,
            
           ));     
        $forms->add('lname',TextType::class,array(
            'disabled'=>true,
            'empty_data'=>$s,
           ));     
        $forms->add('name',TextType::class ,array(
            'disabled'=>true,
            'empty_data'=>$s,
           ));     
        $forms->add('password',TextType::class ,array(
            'disabled'=>true,
            'empty_data'=>$s,
           ));     
        $forms->add('email');
        $forms->add('description');
        $forms->add('ville');
        $forms-> handleRequest($req);
        if ($forms->isSubmitted() && $forms->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em ->remove($u);
            $em -> flush();
            return $this->redirectToRoute('users',['s'=>$nom,'idc'=>$idc]);
        }
        return $this->render('admin/delu.html.twig',['fs'=>$forms->createview(),'s'=>$nom,'idc'=>$idc]);
    }
     /**
     * @Route("/admins{s}{idc}",name="admins")
     */
    public function admins($s , $idc, AdminRepository $admins)
    {
        $findn = $admins->find($idc);
        $nom = $findn->getName();
        $admin = $admins->findAll();
        return $this->render("admin/alladmin.html.twig",['admins'=>$admin,'s'=>$nom,'idc'=>$idc]) ;
    }
    /**
     * @Route("/adda{idc}{s}",name="adda")
     */
    public function adda($s , $idc , Request $req , AdminRepository $admins,UtilisateurRepository $users) /*   ajouuuuuuuuuut admiiiiiiiiiiiin    */
    {
        $findn = $admins->find($idc);
        $nom = $findn->getName();
        $admin = new Admin() ;
        $fadmin = $this->createForm(AdminType::class,$admin);
        $allu = $users->findAll();
       $tab = array();
       foreach ($allu as $us)
         {   
       $tab[$us->getName()]=$us->getName()   ; 
         }
       $fadmin->add('name',ChoiceType::class,
       array(
        
           'choices'=>$tab ,
       ));

        $fadmin ->add('password',PasswordType::class);
        $fadmin-> handleRequest($req);
        if($fadmin->isSubmitted() && $fadmin->isValid() )
        {
          $nom = $admins->findByName($admin->getName());
           if ( empty($nom) ){
               $em = $this->getDoctrine()->getManager(); 
               $em->persist($admin);
               $em->flush();
               return $this->redirectToRoute('admins',['s'=>$nom,'idc'=>$idc]);   
            }else
            $this->addFlash('ermdp','Nom utilisateur déja utilisé , merci de choisir un autre ');
       }
       return $this->render("admin/adda.html.twig",['fu'=>$fadmin->createView(),'s'=>$s,'idc'=>$idc]);
    }
    /**
     * @Route("/updateA{s}{idc}{id}",name="updateA")
     */
    public function modifa($s,$id,$idc ,Request $req, AdminRepository $admins)
    {
        $findn = $admins->find($idc);
        $nom = $findn->getName();
        $a = new Admin();
        $a = $this->getDoctrine()->getRepository(Admin::class)->find($id);
        $nu = $a->getName();
        $mdp = $a->getPassword();
        $form = $this->createForm(AdminType::class,$a);
        $form ->add('name' ,TextType::class,array(
            'disabled'=>false,
            'empty_data'=>$nu,
            
           ))    
        ->add('password',PasswordType::class ,array(
            'disabled'=>false,
            'empty_data'=>$mdp,
            
           ));     

        $form -> handleRequest($req);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em -> flush();
            return $this->redirectToRoute('admins',['s'=>$nom,'idc'=>$idc]);
        }
        return $this->render('admin/modifa.html.twig',['fu'=>$form->createview(),'s'=>$nom,'idc'=>$idc]);
    }
    /**
     * @Route("/addu/{s}/{idc}",name="addu")
     */
    public function addu($s,$idc,Request $req , UtilisateurRepository $reps, AdminRepository $admins)
    {  
        /* ajout userrrrrrrrrrrrrrrrrrrrrrrrrrrrr */ 
        $findn = $admins->find($idc);
        $nom = $findn->getName();
        $user = new Utilisateur();
        $fuser =  $this-> createForm(UtilisateurType::class,$user); 
        $fuser->add('fname',TextType::class , [
            'constraints' => new NotBlank(['message'=>'il faut remplir ce champ'])
        ]
        
        );
        $fuser->add('lname',TextType::class , [
            'constraints' => new NotBlank(['message'=>'il faut remplir ce champ'])
        ]
        
        );
        $fuser->add('name');
        $fuser->add('password',PasswordType::class);
        $fuser->add('email');
        $fuser->add('description');
        $fuser->add('ville');
        $fuser-> handleRequest($req);
        $lesu = $reps->findAll(); 
        if($fuser->isSubmitted() && $fuser->isValid() )
         {
           $nom = $reps->findByName($user->getName());
            if ( empty($nom) ){
                $em = $this->getDoctrine()->getManager(); 
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('users',['s'=>$s,'idc'=>$idc]);
            }else
              $this->addFlash('faute','Nom utilisateur déja utilisé , merci de choisir un autre ');
              
         }
         return $this->render("admin/addu.html.twig",['fu'=>$fuser->createView(),'s'=>$s,'idc'=>$idc]);
    }
    /**
     * @Route("/updatep/{id}/{s}/{idc}",name="updatep")
     */
    public function updatep($id,$idc,$s,Request $req , UtilisateurRepository $users, PiecesRepository $pieces , AdminRepository $admins)
    {
        $findn = $admins->find($idc);
        $nom = $findn->getName();
        $all = $users->findAll();
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
            return $this->redirectToroute('inter',['pieces'=>$allp,'s'=>$nom,'idc'=>$idc]);
        }
        return $this->render('admin/modifp.html.twig',['fp'=>$fpiece->createview(),'s'=>$nom,'idc'=>$idc]);
    }
    /**
     * @Route("/supprp/{id}/{s}/{idc}",name="supprp")
     */
    public function supprp($id,$s,$idc,Request $req , UtilisateurRepository $users ,PiecesRepository $pieces , AdminRepository $admins)
    {
            $findn = $admins->find($idc);
            $nom = $findn->getName();
            $all = $users->findAll();
            $allp = $pieces->findAll();
            $p = new Pieces();
            $p = $this->getDoctrine()->getRepository(Pieces::class)->find($id);
            $fpiece = $this->createForm(PiecesType::class,$p);
            $fpiece->add('drawingnumber',ChoiceType::class,array( 'choices' =>[ 'Empty'=>null , 'bg'=>'bg', 'assm'=>'assm' ]));
            $fpiece->add('utilisateur',TextType::class);      
              $arr2 = array();
              foreach ($allp as $i)
              {   
              $arr2[$i->getPrductnumber()]=$i->getPrductnumber()   ;  }
              $fpiece ->add('prductnumber',ChoiceType::class,array(          
                'choices'=>$arr2 , ));
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
                $em->remove($p);
                $em->flush();
            
             return $this->redirectToroute('inter',['pieces'=>$allp,'s'=>$nom,'idc'=>$idc]);    
            }
            return $this->render('admin/supprimerp.html.twig',['fps'=>$fpiece->createview(),'s'=>$nom,'idc'=>$idc]);
          }
   
    /**
     * @Route("/logout",name="logouta")
     */
    public function logout()
    {
        return $this->redirectToRoute('app_home');
    }
   
    /**
     * @Route("/chercherp{s}{idc}",name="searchp")
     */
    public function chercherp($s,$idc,Request $r , PiecesRepository $pieces , AdminRepository $admin)
    {
        $findn = $admin->find($idc);
        $nom = $findn->getName();    
    $op = new Operation();
    $search = $this-> createForm(OperationType::class,$op); 
    $search->add('searchid');
    $search ->handleRequest($r) ;  
    if( $search->isSubmitted() && $search->isValid() )
    {
        $res = $pieces->find($op->getSearchid());
        return  $this->render('admin/resch.html.twig',['res'=>$res,'s'=>$nom,'idc'=>$idc]);
    }
    return  $this->render('admin/searchp.html.twig',['f'=>$search->createView(),'s'=>$nom,'idc'=>$idc]);
    }
    /**
     * @Route("/choixch{s}{idc}",name="choixch")
     */
    public function choix( UtilisateurRepository $users , $s , $idc ,AdminRepository $admin  )
    {
        $findn = $admin->find($idc);
        $nom = $findn->getName(); 
        return  $this->render('admin/mchoix.html.twig',['s'=>$nom,'idc'=>$idc]);
    }
    
    /**
     * @Route("/chercheru1{s}{idc}",name="searchid")
     */
    public function chercheru1( Request $r , UtilisateurRepository $users, $s , $idc , AdminRepository $admin )
    {
        $findn = $admin->find($idc);
        $nom = $findn->getName(); 
        $op = new Operation();
        $search = $this-> createForm(OperationType::class,$op); 
        $search->add('searchid');
        $search ->handleRequest($r) ;  
        if( $search->isSubmitted() && $search->isValid() )
        {
            $res = $users->find($op->getSearchid());
            return  $this->render('admin/reschu.html.twig',['res'=>$res,'s'=>$nom,'idc'=>$idc]);
        }
        return  $this->render('admin/searchuid.html.twig',['f'=>$search->createView(),'s'=>$s,'idc'=>$idc]);
    }
     /**
     * @Route("/chercheru2{s}{idc}",name="searchname")
     */
    public function chercheru2( Request $r1 , UtilisateurRepository $users, $s , $idc ,AdminRepository $admin )
    {
        $findn = $admin->find($idc);
        $nom = $findn->getName(); 
        $o = new Operation();
        $cherche = $this-> createForm(OperationType::class,$o); 
        $cherche->add('search');
        $cherche->handleRequest($r1) ;  
        if ( $cherche->isSubmitted() )
        {
            $res = $users->findByName($o->getSearch()) ;
            return  $this->render('admin/reschun.html.twig',['res'=>$res,'s'=>$nom,'idc'=>$idc]);
        }
        return  $this->render('admin/searchun.html.twig',['fcher'=>$cherche->createView(),'s'=>$s,'idc'=>$idc]);
    }
    /**
     * @Route("/infosc/{idc}/{s}",name="infosc")
     */
    public function infosc($idc , $s , AdminRepository $admins )
    {   
        $findn = $admins->find($idc);
        $nom = $findn->getName(); 
        $infos = $admins->find($idc);
        return  $this->render('admin/infos.html.twig',['infos'=>$infos,'idc'=>$idc ,'s'=>$nom]);
    }
}