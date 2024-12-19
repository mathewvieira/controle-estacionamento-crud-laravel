<?php

namespace App\Http\Controllers\Api;

use App\Constants\VehicleMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleRequest;
use App\Services\VehicleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VehicleController extends Controller
{
    use ApiResponseTrait;

    private VehicleService $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }

    public function index(Request $request)
    {
        return $this->handleControllerAction(
            fn() =>
            $this->successResponse(
                VehicleMessages::ACTIONS['retrieved'],
                $this->vehicleService->findAllVehicles((int) $request->query('perPage', 5))->toArray(),
                Response::HTTP_OK,
                true
            )
        );
    }

    public function show(string $id)
    {
        return $this->handleControllerAction(
            fn() =>
            $this->successResponse(
                VehicleMessages::ACTIONS['retrieved'],
                $this->vehicleService->findVehicleById($id)->toArray(),
                Response::HTTP_OK
            )
        );
    }

    public function store(VehicleRequest $request)
    {
        return $this->handleControllerAction(
            fn() =>
            $this->successResponse(
                VehicleMessages::ACTIONS['created'],
                $this->vehicleService->createVehicle($request->validated())->toArray(),
                Response::HTTP_CREATED
            )
        );
    }

    public function update(VehicleRequest $request, string $id)
    {
        return $this->handleControllerAction(
            fn() =>
            $this->successResponse(
                VehicleMessages::ACTIONS['updated'],
                $this->vehicleService->updateVehicle($id, $request->validated())->toArray(),
                Response::HTTP_OK
            )
        );
    }

    public function destroy(string $id)
    {
        return $this->handleControllerAction(
            fn() =>
            $this->vehicleService->deleteVehicle($id)
            ? $this->noContentResponse()
            : null
        );
    }

    private function handleControllerAction(callable $action)
    {
        try {
            return $action();

        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse(
                VehicleMessages::ACTIONS['not_found'],
                Response::HTTP_NOT_FOUND
            );

        } catch (\Exception $exception) {
            return $this->errorResponse(
                VehicleMessages::ACTIONS['error'],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [$exception::class]
            );
        }
    }
}