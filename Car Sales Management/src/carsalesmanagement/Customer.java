/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package carsalesmanagement;

/**
 *
 * @author Yauskycoon
 */
public class Customer extends User{
    
    private String custID, password;

    public Customer(String custID, String password, String userName, String email, String phoneNum) {
        super(userName, email, phoneNum);
        this.custID = custID;
        this.password = password;
    }

    public String getCustID() {
        return custID;
    }

    public void setCustID(String custID) {
        this.custID = custID;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }
    
    @Override
    public void PrintUserDetails() {
        System.out.println("----------------------------------------------");
        System.out.println("CUSTOMER DETAILS");
        System.out.println("----------------------------------------------");
        
        System.out.println("Customer ID: " + custID);
        System.out.println("Customer Username: " + userName);
        System.out.println("Customer Email: " + email);
        System.out.println("Customer Phone Number: " + phoneNum);
    }
    
  
}
