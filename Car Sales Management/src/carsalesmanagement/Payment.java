/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package carsalesmanagement;

/**
 *
 * @author Tan Li Min
 */

public class Payment {
    private String paymentID, paymentMethod, datePaid;
    private double finalPrice;
    private Bank bank;
    
    public Payment(){
        
        paymentID=null;
        paymentMethod=null;
        datePaid=null;
        bank=null;
        finalPrice=0.00;
    }

    public Payment(String paymentID, String paymentMethod, String datePaid) {
        this.paymentID = paymentID;
        this.paymentMethod = paymentMethod;
        this.datePaid = datePaid;
    }

    public Payment(String paymentID, String paymentMethod, String datePaid, Bank bank) {
        this.paymentID = paymentID;
        this.paymentMethod = paymentMethod;
        this.datePaid = datePaid;
        this.bank = bank;
    }

    public Payment(Bank bank) {
        this.bank = bank;
    }

    public String getPaymentID() {
        return paymentID;
    }

    public void setPaymentID(String paymentID) {
        this.paymentID = paymentID;
    }

    public String getPaymentMethod() {
        return paymentMethod;
    }

    public void setPaymentMethod(String paymentMethod) {
        this.paymentMethod = paymentMethod;
    }

    public String getDatePaid() {
        return datePaid;
    }

    public void setDatePaid(String datePaid) {
        this.datePaid = datePaid;
    }

    public Bank getBank() {
        return bank;
    }

    public void setBank(Bank bank) {
        this.bank = bank;
    }

    public double getFinalPrice() {
        return finalPrice;
    }

    public void setFinalPrice(double finalPrice) {
        this.finalPrice = finalPrice;
    }
    
    
    public void printPaymentDetail(){
        System.out.println("----------------------------------------------");
        System.out.println("PAYMENT DETAILS");
        System.out.println("----------------------------------------------");
        
        System.out.println("Payment ID: " + paymentID);
        System.out.println("Payment Method: " + paymentMethod);
        System.out.println("Payment Date: " + datePaid);
        
    }
    
    public void printPaymentDetail1(){
        
        System.out.println("----------------------------------------------");
        System.out.println("PAYMENT DETAILS");
        System.out.println("----------------------------------------------");
        
        System.out.println("Payment ID: " + paymentID);
        System.out.println("Payment Method: " + paymentMethod);
        System.out.println("Payment Date: " + datePaid);
        
        
        System.out.println("\n----------------------------------------------");
        System.out.println("BANK DETAILS");
        System.out.println("----------------------------------------------");
        
        System.out.println("Bank Name: " + bank.getBankName());
        System.out.println("Lender Name: " + bank.getLenderName());
        System.out.println("Lender Phone Number: " + bank.getLenderPhoneNo());
        System.out.println("Lender Salary: " + bank.getLenderSalary());
        System.out.println("Lender Account: " + bank.getAccount());
        System.out.println("Guarantor Name: " + bank.getGuarantor());
        System.out.println("Guarantor Phone Number: " + bank.getGuarantorPhoneNo());
        System.out.println("Loan Amount " + bank.getLoanAmount());
        System.out.println("Loan Rate: " + bank.getLoanRate());
        System.out.println("Loan Period: " + bank.getLoanPeriod());
        System.out.println("Loan Start Date: " + bank.getStartDate());
        
        
        
        System.out.println("\nMonthly Repayment: " + bank.calMonthlyRepayment());
    }
}
