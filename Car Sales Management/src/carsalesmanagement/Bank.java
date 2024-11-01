/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package carsalesmanagement;

/**
 *
 * @author Tan Li Min
 */
public class Bank {
    private String bankName, lenderName, guarantor, startDate;
    private int account, lenderPhoneNo, guarantorPhoneNo,lenderSalary, loanPeriod;
    private double loanAmount, loanRate;

    public Bank(){
        bankName = null;
        lenderName = null;
        lenderPhoneNo = 0;
        lenderSalary = 0;
        account =0;
        guarantor = null;
        guarantorPhoneNo = 0;
        loanAmount = 0;
        loanRate = 0;
        loanPeriod = 0;
        startDate = null;
        
    }

    public Bank(String bankName, String lenderName, int lenderPhoneNo, int lenderSalary, int account, String guarantor, int guarantorPhoneNo, double loanAmount, double loanRate, int loanPeriod, String startDate) {
        this.bankName = bankName;
        this.lenderName = lenderName;
        this.lenderPhoneNo = lenderPhoneNo;
        this.lenderSalary = lenderSalary;
        this.account = account;
        this.guarantor = guarantor;
        this.guarantorPhoneNo = guarantorPhoneNo;
        this.loanAmount = loanAmount;
        this.loanRate = loanRate;
        this.loanPeriod = loanPeriod;
        this.startDate = startDate;
    }
    
   

    public String getBankName() {
        return bankName;
    }

    public void setBankName(String bankName) {
        this.bankName = bankName;
    }

    public String getLenderName() {
        return lenderName;
    }

    public void setLenderName(String lenderName) {
        this.lenderName = lenderName;
    }

    public String getGuarantor() {
        return guarantor;
    }

    public void setGuarantor(String guarantor) {
        this.guarantor = guarantor;
    }

    public String getStartDate() {
        return startDate;
    }

    public void setStartDate(String startDate) {
        this.startDate = startDate;
    }

    public int getAccount() {
        return account;
    }

    public void setAccount(int account) {
        this.account = account;
    }

    public int getLenderPhoneNo() {
        return lenderPhoneNo;
    }

    public void setLenderPhoneNo(int lenderPhoneNo) {
        this.lenderPhoneNo = lenderPhoneNo;
    }

    public int getLenderSalary() {
        return lenderSalary;
    }

    public void setLenderSalary(int lenderSalary) {
        this.lenderSalary = lenderSalary;
    }

    public int getLoanPeriod() {
        return loanPeriod;
    }

    public void setLoanPeriod(int loanPeriod) {
        this.loanPeriod = loanPeriod;
    }

    public double getLoanAmount() {
        return loanAmount;
    }

    public void setLoanAmount(double loanAmount) {
        this.loanAmount = loanAmount;
    }

    public double getLoanRate() {
        return loanRate;
    }

    public void setLoanRate(double loanRate) { 
        this.loanRate = loanRate;
    }

    public int getGuarantorPhoneNo() {
        return guarantorPhoneNo;
    }

    public void setGuarantorPhoneNo(int guarantorPhoneNo) {
        this.guarantorPhoneNo = guarantorPhoneNo;
    }
    
    public double calMonthlyRepayment(){
        double monthly = getLoanAmount() * getLoanRate() / 100 / 12;
        return monthly;
    }
    
    
}
