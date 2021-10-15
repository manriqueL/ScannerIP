<?php


namespace App\Controller;


use App\Entity\Permiso;
use App\Entity\Role;
use App\Repository\PermisoRepository;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PermisoController extends BaseController
{

    private $permisoRepository;
    private $roleRepository;
    private $entityManager;

    public function __construct(PermisoRepository $permisoRepository, RoleRepository $roleRepository,EntityManagerInterface $entityManager)
    {
        $this->permisoRepository = $permisoRepository;
        $this->roleRepository = $roleRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/permiso/new/{id}", name="new_permiso", requirements={"id":"\d+"})
     * @IsGranted("ROLES_CREAR")
     */
    public function new(Request $request, Role $role){

        $userId = $this->getUser()->getId();
        if ($this->isCsrfTokenValid('guardarRoles'.$userId, $request->request->get('_token'))) {
            
            $arrayPermisos = [];
            foreach($request->request->all() as $nombre=>$valor){
                if($valor == 'on'){
                    array_push($arrayPermisos, $nombre);
                }
            }
            $borrarPermisosViejos = $this->permisoRepository->delete($role);
            if($borrarPermisosViejos === true){
                $asignarPermisosNuevos = $this->permisoRepository->asignarRoles($role, $arrayPermisos);
                if($asignarPermisosNuevos === true){
                    $this->addFlash("success-modificado","");
                }else{
                    $this->addFlash("error-modificado","Error al modificar los permisos. Inténtelo nuevamente");
                }
                
            }else{
                $this->addFlash("error-modificado","");
            }
        }
        return $this->redirectToRoute('index_roles'); 
    }


    /**
     * @Route("/permiso/delete/{id}",name="delete_permiso", requirements={"id":"\d+"})
     * @IsGranted("ROLES_ELIMINAR")
     */
    public function delete(Request $request, Permiso $permiso){
        /* if ($this->isCsrfTokenValid('delete'.$rol->getId(), $request->request->get('_token'))) {
            $resp = $this->rolesRepository->delete($rol);
            if($resp === true){
                $this->addFlash("success-eliminado","");
            }else{
                $this->addFlash("error-eliminado","");
            }
        } */
        return $this->redirectToRoute('index_roles');          
    }
    
}
