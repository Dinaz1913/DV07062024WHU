<?php
	
	namespace Reelz\Warehouse;
	
	use Monolog\Handler\StreamHandler;
	use Monolog\Level;
	use Monolog\Logger;
	
	class WarehouseManager
	{
		private array $products = [];
		private Logger $logger;
		
		public function __construct()
		{
			$this->logger = new Logger('Warehouse');
			$this->logger->pushHandler
            (new StreamHandler
            (__DIR__ . '/../logs/warehouse.log',
                Level::Info));
			$this->loadProducts();
		}
		
		public function addProduct
        (
            string $name,
            int $amountOfUnits
        ): void
		{
			$product = new Product($name, $amountOfUnits);
			$this->products[$product->getId()] = $product;
			$this->logChange('add', $product);
			$this->saveProducts();
		}
		
		public function updateProductAmount
        (
            string $id,
            int $amount
        ): void
		{
			if (isset($this->products[$id])) {
				$this->products[$id]->setAmountOfUnits($amount);
				$this->logChange('update',
                    $this->products[$id]);
				$this->saveProducts();
			}
		}
		
		public function withdrawProduct
        (
            string $id,
            int $amount
        ): void
		{
			if (isset($this->products[$id]) &&
                $this->products[$id]->
                getAmountOfUnits() >= $amount) {
				$newAmount =
                    $this->products[$id]->
                    getAmountOfUnits() - $amount;
				$this->products[$id]->
                setAmountOfUnits($newAmount);
				$this->logChange('withdraw',
                    $this->products[$id]);
				$this->saveProducts();
			}
		}
		
		public function deleteProduct
        (
            string $id
        ): void
		{
			if (isset($this->products[$id])) {
				$product = $this->products[$id];
				unset($this->products[$id]);
				$this->logChange('delete', $product);
				$this->saveProducts();
			}
		}
		
		public function getProducts(): array
		{
			return $this->products;
		}
		
		private function loadProducts(): void
		{
			if (file_exists(__DIR__ .
                '/../data/products.json')) {
				$data = json_decode(file_get_contents
                (__DIR__ .
                    '/../data/products.json'), true);
				foreach ($data as $productData) {
					$this->products[$productData['id']]
                        = Product::fromArray($productData);
				}
			}
		}
		
		private function saveProducts(): void
		{
			$data =
                array_map(function
                (Product $product) {
				return $product->toArray();
			    }
            , $this->products);
			file_put_contents(__DIR__ .
                '/../data/products.json',
                json_encode($data, JSON_PRETTY_PRINT));
		}
		
		private function logChange
        (
            string $action,
            Product $product
        ): void
		{
			$this->logger->info(sprintf(
				'Action: %s, Product ID: %s, Name: %s, Amount: %d, Timestamp: %s',
				$action,
				$product->getId(),
				$product->getName(),
				$product->getAmountOfUnits(),
				$product->getLastUpdatedTime()
			));
		}
	}
