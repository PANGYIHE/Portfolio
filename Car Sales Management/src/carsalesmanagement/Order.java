/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package carsalesmanagement;

/**
 *
 * @author Nicholas Tan
 */
public class Order {
     
    private String orderDate;
    private int stock;
    private double totalPriceCar, totalPriceSparePart, priceCar, priceSparePart;
    private Customer customer;
    private Car car;
    private SparePart sparePart;
    private Payment payment;
    private Award award;
    
    public Order() {
        orderDate="";
        stock=0;
        car=null;
        sparePart=null;
        totalPriceCar=0.00;
        totalPriceSparePart=0.00;
        priceCar=0.00;
        priceSparePart=0.00;
        payment=null;
        award=null;
    }

    public Order(String orderDate, int stock, Car car, Customer customer) {
        this.orderDate = orderDate;
        this.stock = stock;
        this.car = car;
        this.customer=customer;
    }
    
    public Order(String orderDate, int stock, SparePart sparePart, Customer customer) {
        this.orderDate = orderDate;
        this.stock = stock;
        this.sparePart=sparePart;
        this.customer=customer;
    }
    
    public String getOrderDate() {
        return orderDate;
    }

    public void setOrderDate(String orderDate) {
        this.orderDate = orderDate;
    }

    public int getStock() {
        return stock;
    }

    public void setStock(int stock) {
        this.stock = stock;
    }

    public Customer getCustomer() {
        return customer;
    }

    public void setCustomer(Customer customer) {
        this.customer = customer;
    }

    public Car getCar() {
        return car;
    }

    public void setCar(Car car) {
        this.car = car;
    }

    public SparePart getSparePart() {
        return sparePart;
    }

    public void setSparePart(SparePart sparePart) {
        this.sparePart = sparePart;
    }

    public double getTotalPriceCar() {
        return totalPriceCar;
    }

    public void setTotalPriceCar(double totalPriceCar) {
        this.totalPriceCar = totalPriceCar;
    }

    public double getPriceCar() {
        return priceCar;
    }

    public void setPriceCar(double priceCar) {
        this.priceCar = priceCar;
    }
    
    
    public double calPriceCar(){
        
        priceCar=car.getPrice()*stock;
        
        return priceCar;
    }

    public void printOrderCar(){
        
        car.ViewCarDetails(); 
        
        System.out.println("Quantity order: "+stock);
        System.out.println("\n");
       
    }

    public double getTotalPriceSparePart() {
        return totalPriceSparePart;
    }

    public void setTotalPriceSparePart(double totalPriceSparePart) {
        this.totalPriceSparePart = totalPriceSparePart;
    }

    public double getPriceSparePart() {
        return priceSparePart;
    }

    public void setPriceSparePart(double priceSparePart) {
        this.priceSparePart = priceSparePart;
    }
    
    public double calPriceSparePart(){
        
        priceSparePart=sparePart.getSparePartPrice()*stock;
        
        return priceSparePart;
    }
    
    public void printOrderSparePart(){
        
        sparePart.printSparePartDetails();
        
        System.out.println("Quantity spare part: " +stock );
        System.out.println("\n");
       
    }
    
    public int calAward(){
        
        int award=0;
        
        if(car.showAdminID().equals("AA001")){
            
             award=0;
            
        }
        else if(car.showAdminID().equals("AA002")){
            
            award=1;
            
        }
        else if(car.showAdminID().equals("AA003")){
            
            award=2;
            
        }
        
        return award;
        
        
    }

    public Payment getPayment() {
        return payment;
    }

    public void setPayment(Payment payment) {
        this.payment = payment;
    }

    public Award getAward() {
        return award;
    }

    public void setAward(Award award) {
        this.award = award;
    }

    public void printAward(){
        award.printAward();
        
    }
    
}
