<?php

// The task: What principle is violated and what is wrong?

class Customer
{
    private $currentOrder = null;

    public function buyItems()
    {
        if (is_null($this->currentOrder)) {
            return false;
        }

        $processor = new OrderProcessor();
        return $processor->checkout($this->currentOrder);
    }

    public function addItem($item)
    {
        if (is_null($this->currentOrder)) {
            $this->currentOrder = new Order();
        }
        return $this->currentOrder->addItem($item);
    }

    public function deleteItem($item)
    {
        if (is_null($this->currentOrder)) {
            return false;
        }
        return $this->currentOrder->deleteItem($item);
    }
}

class OrderProcessor
{
    public function checkout($order)
    {
        /*...*/
    }
}