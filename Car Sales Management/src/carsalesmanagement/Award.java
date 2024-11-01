/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package carsalesmanagement;

/**
 *
 * @author Nicholas Tan
 */
public class Award {
    
    private Admin admin;
    private double bonus,highest;

    public Award(Admin admin,double highest) {
        this.admin = admin;
        this.bonus = 10000.00;
        this.highest=highest;
    }

    public Admin getAdmin() {
        return admin;
    }

    public void setAdmin(Admin admin) {
        this.admin = admin;
    }

    public double getBonus() {
        return bonus;
    }

    public void setBonus(double bonus) {
        this.bonus = bonus;
    }

    public double getHighest() {
        return highest;
    }

    public void setHighest(double highest) {
        this.highest = highest;
    }

    public void printAward(){
        
        System.out.println("Admin ID: " +admin.getAdminID());
        
        System.out.println("Admin Name: " +admin.getUserName());
        
        System.out.println("\nTotal Sales: " +getHighest());
        
        System.out.println("Bonus earn: " +getBonus());

        
    }
  
    
}
