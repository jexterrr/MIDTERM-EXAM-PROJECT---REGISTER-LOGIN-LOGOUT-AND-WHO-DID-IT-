<?php
class crud
{
     private $conn;
     private $table_name = "appointments";

     public $id;
     public $petName;
     public $service;
     public $providerName;
     public $appointDate;

     public function __construct($db)
     {
          $this->conn = $db;
     }

     public function read($table, $id)
     {
          if ($table == "appointments") {
               $query = "SELECT * FROM " . $table . " ORDER BY $id DESC";
               $stmt = $this->conn->prepare($query);
               $stmt->execute();
               return $stmt;
          } else {
               $query = "SELECT * FROM " . $table . " ORDER BY $id DESC";
               $stmt = $this->conn->prepare($query);
               $stmt->execute();
               return $stmt->fetchAll(PDO::FETCH_ASSOC);
          }
     }

     // Method to create a new developer record
     public function create()
     {
          // SQL query to insert a new developer
          $query = "INSERT INTO " . $this->table_name . "
                    SET pet_name = :petName, service_type = :service, provider_name = :provider, appointment_date = :appointDate, createBy = :createBy";

          // Prepare the query
          $stmt = $this->conn->prepare($query);

          // Sanitize and bind parameters
          $stmt->bindParam(":petName", $this->petName);
          $stmt->bindParam(":service", $this->service);
          $stmt->bindParam(":provider", $this->providerName);
          $stmt->bindParam(":appointDate", $this->appointDate);
          $stmt->bindParam(":createBy", $_SESSION['username']);

          // Execute the query and check if successful
          if ($stmt->execute()) {
               return true;
          }

          return false;
     }

     // Method to get a single developer record
     public function readOne()
     {
          $query = "SELECT * FROM " . $this->table_name . " WHERE appointment_id = ? LIMIT 0,1";
          $stmt = $this->conn->prepare($query);

          $stmt->bindParam(1, $this->id);
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          $this->petName = $row['pet_name'] ?? null;
          $this->service = $row['service_type'] ?? null;
          $this->providerName = $row['provider_name'] ?? null;
          $this->appointDate = $row['appointment_date'] ?? null;
     }


     // Method to update a developer record
     public function update()
     {
          $query = "UPDATE " . $this->table_name . "
               SET pet_name = :petName, service_type =:service, provider_name = :provider, appointment_date =:appointDate, updateBy = :updateBy
               WHERE appointment_id = :id";

          $stmt = $this->conn->prepare($query);

          // Bind new values
          $stmt->bindParam(":petName", $this->petName);
          $stmt->bindParam(":service", $this->service);
          $stmt->bindParam(":appointDate", $this->appointDate);
          $stmt->bindParam(":provider", $this->providerName);

          $updateByValue = $_SESSION['username'] . ' | ' . date('Y-m-d H:i:s');
          $stmt->bindParam(":updateBy", $updateByValue);

          $stmt->bindParam(":id", $this->id);

          if ($stmt->execute()) {
               return true;
          }

          return false;
     }

     public function delete()
     {
          $query = "DELETE FROM " . $this->table_name . " WHERE appointment_id = :id";

          // Prepare query
          $stmt = $this->conn->prepare($query);

          // Bind the developer ID
          $stmt->bindParam(":id", $this->id);

          if ($stmt->execute()) {
               return true;
          }

          return false;
     }
}
