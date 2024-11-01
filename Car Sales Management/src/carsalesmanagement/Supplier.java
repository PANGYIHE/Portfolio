/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package carsalesmanagement;

/**
 *
 * @author Nicholas Tan
 */
public class Supplier {
    
    private String supplierName,supplierPhoneNum;
    
    public Supplier(){
        supplierName="";
        supplierPhoneNum="";
    }

    public Supplier(String supplierName, String supplierPhoneNum) {
        this.supplierName = supplierName;
        this.supplierPhoneNum = supplierPhoneNum;
    }

    public String getSupplierName() {
        return supplierName;
    }

    public void setSupplierName(String supplierName) {
        this.supplierName = supplierName;
    }

    public String getSupplierPhoneNum() {
        return supplierPhoneNum;
    }

    public void setSupplierPhoneNum(String supplierPhoneNum) {
        this.supplierPhoneNum = supplierPhoneNum;
    }

    public void printCarSupplier() {
        System.out.println(supplierName);
    }
    
    
    
}
