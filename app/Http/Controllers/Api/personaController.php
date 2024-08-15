<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PersonaController extends Controller
{

    public function index(): JsonResponse
    {
        $personas = Persona::all();
        return response()->json($personas);
    }
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'email' => 'required|email|unique:persona,email',
                'edad' => 'required|integer|min:0',
                'sexo' => 'required|string|size:1',
                'imagen' => 'nullable|string|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validatedData = $validator->validated();

            if ($request->hasFile('imagen')) {
                $validatedData['imagen'] = $request->file('imagen')->store('imagenes', 'public');
            } else {
                $validatedData['imagen'] = 'storage/images/default.jpg'; // Ruta de la imagen por defecto
            }

            $persona = Persona::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Persona creada con éxito',
                'persona' => $persona
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de base de datos',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }
        $persona = Persona::find($id);

        if (!$persona) {
            return response()->json([
                'success' => false,
                'message' => 'Persona no encontrada'
            ], 400);
        }

        return response()->json($persona);
    }
    public function destroy(int $id): JsonResponse
    {
        $persona = Persona::findOrFail($id);
        $persona->delete();

        return response()->json(['success' => true, 'message' => 'Persona eliminada con éxito.']);
    }
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación del ID',
                    'errors' => $validator->errors()
                ], 400);
            }
            $persona = Persona::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'email' => 'required|email|unique:persona,email,' . $persona->id,
                'edad' => 'required|integer|min:0',
                'sexo' => 'required|string|size:1',
                'imagen' => 'nullable|string|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validatedData = $validator->validated();

            if ($request->hasFile('imagen')) {
                $validatedData['imagen'] = $request->file('imagen')->store('imagenes', 'public');
            }

            $persona->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Persona actualizada con éxito',
                'persona' => $persona
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Persona no encontrada',
                'error' => $e->getMessage()
            ], 400);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de base de datos',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updatePartial(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación del ID',
                    'errors' => $validator->errors()
                ], 400);
            }

            $persona = Persona::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nombre' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:persona,email,' . $persona->id,
                'edad' => 'sometimes|required|integer|min:0',
                'sexo' => 'sometimes|required|string|size:1',
                'imagen' => 'sometimes|nullable|string'
            ]);


            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validatedData = $validator->validated();

            if ($request->hasFile('imagen')) {
                $validatedData['imagen'] = $request->file('imagen')->store('imagenes', 'public');
            }

            $persona->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Persona actualizada con éxito',
                'persona' => $persona
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Persona no encontrada',
                'error' => $e->getMessage()
            ], 400);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de base de datos',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
