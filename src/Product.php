<?php
	
	namespace Reelz\Warehouse;
	
	use Ramsey\Uuid\Uuid;
	
    class Product
	{
		private string $id;
		private string $name;
		private string $dateOfCreation;
		private string $lastUpdatedTime;
		private int $amountOfUnits;
		
		public function __construct(string $name, int $amountOfUnits)
		{
			$this->id = Uuid::uuid4()->toString();
			$this->name = $name;
			$this->dateOfCreation = date('Y-m-d H:i:s');
			$this->lastUpdatedTime = date('Y-m-d H:i:s');
			$this->amountOfUnits = $amountOfUnits;
		}
		
		public function getId():
        string { return $this->id; }
		public function getName():
        string { return $this->name; }
		public function getDateOfCreation():
        string { return $this->dateOfCreation; }
		public function getLastUpdatedTime():
        string { return $this->lastUpdatedTime; }
		public function getAmountOfUnits():
        int { return $this->amountOfUnits; }
		public function setAmountOfUnits
        (int $amount): void {
			$this->amountOfUnits = $amount;
			$this->updateLastUpdatedTime();
		}
		private function updateLastUpdatedTime(): void {
			$this->lastUpdatedTime = date('Y-m-d H:i:s');
		}
		public function toArray(): array {
			return [
				'id' => $this->id,
				'name' => $this->name,
				'dateOfCreation' => $this->dateOfCreation,
				'lastUpdatedTime' => $this->lastUpdatedTime,
				'amountOfUnits' => $this->amountOfUnits,
			];
		}
		public static function fromArray(array $data): self {
			$product = new self($data['name'], $data['amountOfUnits']);
			$product->id = $data['id'];
			$product->dateOfCreation = $data['dateOfCreation'];
			$product->lastUpdatedTime = $data['lastUpdatedTime'];
			return $product;
		}
	}
