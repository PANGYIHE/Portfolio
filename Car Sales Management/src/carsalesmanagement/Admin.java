/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package carsalesmanagement;

/**
 *
 * @author Yauskycoon
 */
public class Admin extends User{
    
    private String adminID, password; 

    public Admin(String adminID, String password, String userName, String email, String phoneNum) {
        super(userName, email, phoneNum);
        this.adminID = adminID;
        this.password = password;
    }
    
    public String getAdminID() {
        return adminID;
    }

    public void setAdminID(String adminID) {
        this.adminID = adminID;
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
        System.out.println("ADMIN DETAILS");
        System.out.println("----------------------------------------------");
        
        System.out.println("Admin ID: " + adminID);
        System.out.println("Admin Username: " + userName);
        System.out.println("Admin Email: " + email);
        System.out.println("Phone Number: " + phoneNum);
    }
    
    
}
