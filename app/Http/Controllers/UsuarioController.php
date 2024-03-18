<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarUsuarioRequest;
use App\Http\Requests\CambiarRolUsuarioRequest;
use App\Http\Requests\ConEliminadosOrdenDireccionRequest;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ConEliminadosOrdenDireccionRequest $request)
    {
        $parametros = $request->validated();
        $queryBuilder = User::with(['dependencia']);

        if (isset($parametros['con_eliminados'])) {
            $queryBuilder->withTrashed();
        }

        if (isset($parametros['orden_direccion'])) {
            $queryBuilder->orderBy('id', $parametros['orden_direccion']);
        }

        $usuarios = $queryBuilder->get();

        return response()->jsonResponse('Usuarios recuperados', $usuarios, 200);
    }

    protected function findWithTrashed(string $id)
    {
        $usuario = User::withTrashed()->find($id);

        if (is_null($usuario)) {
            throw new NotFoundHttpException('Usuario no encontrado');
        }

        return $usuario;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = $this->findWithTrashed($id);

        return response()->jsonResponse('Usuario recuperado', $usuario, 200);
    }
    
    public function cambiarRol(CambiarRolUsuarioRequest $request, string $id)
    {
        $usuario = $this->findWithTrashed($id);
        $parametros = $request->validated();
        $usuario->rol = $parametros['rol'];
        $usuario->save();
        
        return response()->jsonResponse('Rol de usuario actualizado', $usuario, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = $this->findWithTrashed($id);

        $usuario->forceDelete();

        return response()->jsonResponse('Usuario eliminado', $usuario, 200);
    }

        /**
     * Soft delete the specified resource from storage.
     */
    public function softDestroy(int $id)
    {
        $usuario = $this->findWithTrashed($id);

        $usuario->delete();

        return response()->jsonResponse('Usuario desactivado', $usuario, 200);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(int $id)
    {
        $usuario = $this->findWithTrashed($id);

        $usuario->restore();

        return response()->jsonResponse('Usuario activado', $usuario, 200);
    }

    public function update(ActualizarUsuarioRequest $request, string $id)
    {
        $usuario = $this->findWithTrashed($id);
        
        $usuario->update($request->validated());

        return response()->jsonResponse('Usuario actualizado', $usuario, 200);
    }
}
