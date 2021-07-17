<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{

  public function count($userName, $options = [])
  {
      return Order::with($this->__with($options))
        ->where([
        //  'user.name' => $userName,
        ])->count();
  }

  public function findAll($userName, $options = [])
  {
      $whereHas = function ($query) use ($userName) {
          $query->where('name', 'like', "%$userName%");
      };

      $query = Order::with(['user' => $whereHas])
        ->whereHas('user', $whereHas)
        ->orderBy('created_at', 'desc')
        ->orderBy('id', 'asc');

      if (!empty($options['paging'])) {
        return $query
          ->paginate($options['paging']);
      } else {
        return $query
          ->get();
      }
  }

  public function findById($id, $options = [])
  {
      return Order::with($this->__with($options))
          ->where([
              'id' => $id
          ])
          ->first();
  }

  private function __with($options = [])
  {
      $with = [];
      if (!empty($options['with:user'])) {
          $with[] = 'user';
      }
      if (!empty($options['with:stock'])) {
          $with[] = 'stock';
      }
      return $with;
  }

  public function store(
      $id,
      $stockId,
      $userId,
      $price,
      $quantity
  ) {
      $order = new Order();
      $order->id = $id;
      $order->stock_id = $stockId;
      $order->user_id = $userId;
      $order->price = $price;
      $order->quantity = $quantity;

      $order->save();

      return $order;
  }

  public function update(
    $id,
    $stockId,
    $userId,
    $price,
    $quantity
  ) {
      $order = $this->findById($id);
      $order->stock_id = $stockId;
      $order->user_id = $userId;
      $order->price = $price;
      $order->quantity = $quantity;
      $order->save();

      return $order;
  }

  public function delete(
    $id
  ) {
      $order = $this->findById($id);
      $order->delete();

      return $order;
  }

}
