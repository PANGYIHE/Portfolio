/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package carsalesmanagement;

/**
 *
 * @author Nicholas Tan
 */
public class Car {
    
    private String carID, model, arriveDate;
    private double price;
    private Admin admin;
    private Supplier supplier;

    public Car(String carID, String model, String arriveDate, double price, Admin admin) {
        this.carID = carID;
        this.model = model;
        this.arriveDate = arriveDate;
        this.price = price;
        this.admin = admin;
        this.supplier = new Supplier();
    }

    
    public Admin getAdmin() {
        return admin;
    }

    public void setAdmin(Admin admin) {
        this.admin = admin;
    }

    public Supplier getSupplier() {
        return supplier;
    }

    public void setSupplier(Supplier supplier) {
        this.supplier = supplier;
    }
    
    public String getCarID() {
        return carID;
    }

    public void setCarID(String carID) {
        this.carID = carID;
    }

    public String getModel() {
        return model;
    }

    public void setModel(String model) {
        this.model = model;
    }

    public String getArriveDate() {
        return arriveDate;
    }

    public void setArriveDate(String arriveDate) {
        this.arriveDate = arriveDate;
    }

    public double getPrice() {
        return price;
    }

    public void setPrice(double price) {
        this.price = price;
    }

    
    public void ViewCarDetails(){
        
                System.out.println("Car ID: " +carID);
                System.out.println("Car model: "+model);
                System.out.println("Arrive date: "+arriveDate);
                System.out.println("price: "+price);

                System.out.println("\nManage by: "+ admin.getAdminID());  
                
                System.out.println("Supplier name: " +supplier.getSupplierName());
                        
                System.out.println("\n");
        
    }
    
    public int DeleteCarDetails(String deleted){
        int found=0;
        
                if(deleted.equals(carID)){
                    
                    System.out.println("\nFound !");                
                
                    found=1;
                    
                }
                
                return found;
    }
    
  public String showAdminID(){
      
      String showAdminID;
      
      showAdminID=admin.getAdminID();
      
      
      return showAdminID;
  }
    

}
