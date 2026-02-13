<?php

namespace App\Http\Controllers\Api;

use App\Models\Alert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlertsController extends BaseController
{
    /**
     * Get all alerts
     */
    public function index(Request $request): JsonResponse
    {
        $query = Alert::with('product');

        if ($request->has('type')) {
            $query->where('type', $request->get('type'));
        }

        if ($request->has('severity')) {
            $query->where('severity', $request->get('severity'));
        }

        if ($request->has('unread') && $request->get('unread')) {
            $query->where('is_read', false);
        }

        $alerts = $query->orderBy('created_at', 'desc')
                        ->paginate($request->get('per_page', 15));

        return $this->sendSuccess($alerts);
    }

    /**
     * Mark alert as read
     */
    public function markAsRead(Alert $alert): JsonResponse
    {
        $alert->is_read = true;
        $alert->save();

        return $this->sendSuccess($alert, 'Alert marked as read');
    }

    /**
     * Mark all alerts as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        Alert::where('is_read', false)->update(['is_read' => true]);

        return $this->sendSuccess([], 'All alerts marked as read');
    }

    /**
     * Get unread alerts count
     */
    public function unreadCount(): JsonResponse
    {
        $count = Alert::where('is_read', false)->count();

        return $this->sendSuccess(['count' => $count]);
    }

    /**
     * Delete alert
     */
    public function destroy(Alert $alert): JsonResponse
    {
        $alert->delete();
        return $this->sendSuccess([], 'Alert deleted successfully');
    }
}
