/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package carsalesmanagement;

/**
 *
 * @author Nicholas Tan
 */
public class SparePart {
    
    private String sparePartID, sparePartName, category;
    private double sparePartPrice;
    private Supplier supplier;
    
    public SparePart(String sparePartID, String sparePartName, String category, double sparePartPrice) {
        this.sparePartID=sparePartID;
        this.sparePartName = sparePartName;
        this.category = category;
        this.sparePartPrice = sparePartPrice;
        this.supplier= new Supplier();
    }

    public String getSparePartName() {
        return sparePartName;
    }

    public void setSparePartName(String sparePartName) {
        this.sparePartName = sparePartName;
    }

    public String getCategory() {
        return category;
    }

    public void setCategory(String category) {
        this.category = category;
    }
    
    public double getSparePartPrice() {
        return sparePartPrice;
    }

    public void setSparePartPrice(double sparePartPrice) {
        this.sparePartPrice = sparePartPrice;
    }

    public Supplier getSupplier() {
        return supplier;
    }

    public void setSupplier(Supplier supplier) {
        this.supplier = supplier;
    }

    public String getSparePartID() {
        return sparePartID;
    }

    public void setSparePartID(String sparePartID) {
        this.sparePartID = sparePartID;
    }
    
    
    public void printSparePartDetails(){
        
        System.out.println("Spare part ID: "+getSparePartID());
        System.out.println("Spare Part Name: " +getSparePartName());
        System.out.println("Category: "+getCategory());
        System.out.println("Price: " +getSparePartPrice());
        
        System.out.println("Supplier by: "+supplier.getSupplierName());
        
        System.out.println("\n");
                
        
    }
    
    public int deleteSparePartDetails (String sparePartDelete){
        int found=0;
        
        if(sparePartDelete.equals(sparePartID)){
                    
                    System.out.println("\nFound !");                
                
                    found=1;
                    
                }
        
        return found;
    }
}
