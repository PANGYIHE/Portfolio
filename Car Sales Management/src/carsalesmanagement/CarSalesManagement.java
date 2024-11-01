/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Main.java to edit this template
 */
package carsalesmanagement;

import carsalesmanagement.GUI.CarSalesManagement_Main;

import javax.swing.JFrame;
import java.util.Scanner;

/**
 *
 * @author Nicholas Tan,Tan Li Min, Yau De En 
 */
public class CarSalesManagement{


    
    
    public static void main(String[] args) {
        
        CarSalesManagement_Main guiMain= new CarSalesManagement_Main();
        guiMain.setVisible(true);
        
/*      int option, i = 0, loginNO = 0, found=0, carIDExist=1, supplierNo=0, sparePartIDExist=1, orderCarID=0, orderSparePartID=0, order, orderList, deleteOrderList, index=0, loan, viewLoan, paymentWithLoan=0, paymentWithoutLoan=0;
        double finalPriceCar=0, finalPriceSparePart=0, finalPrice=0;
         
        //Array for admin
        Admin[] admin = new Admin[3];
        
        admin[0] = new Admin("AA001", "987654", "Edmund", "edmund1@gmail.com", "0123249292");
        admin[1] = new Admin("AA002", "GH9635", "Nicholas", "nic111@hotmail.com", "0124750884");
        admin[2] = new Admin("AA003", "HIHI88", "Eugene", "eu999@gmail.com", "0175840833");
        
        //Array for customer
        Customer[] customer = new Customer[3];
        
        customer[0] = new Customer("CC001", "123456", "Christopher", "Chris@outlook.com", "0179517538");
        customer[1] = new Customer("CC002", "Samy555", "Samy", "Samy555@hotmail.com", "01195519938");
        customer[2] = new Customer("CC003", "Kath999", "Katherine", "kathy88@hotmail.com", "0139562538");
        
        
        //Array for supplier   
        Supplier[] supplier = new Supplier[3];
        
        supplier[0] = new Supplier("Auto Bavaria Kuala Lumpur", "0320564288");
        supplier[1] = new Supplier("Auto Bavaria Sungai Besi", "0392233200");
        supplier[2] = new Supplier("ccBMW Service Centre Kuala Lumpur", "0320564288");
        
        //Array for Spare Part
        SparePart[] sparePart = new SparePart[10];
        
        sparePart[0] = new SparePart("SP001","Accelerator Pedal for BMW iX xDrive 40", "Pedals", 1150.00);
        sparePart[1] = new SparePart("SP002","M Sports STRNG WHL, Airbag for BMW iX3 M Sport", "Steering", 7200.00);
        sparePart[2] = new SparePart("SP003","Gear Seelector Switch for BMW XM", "Gear",  4250.00);
        
        sparePart[0].setSupplier(supplier[0]);
        sparePart[1].setSupplier(supplier[1]);
        sparePart[2].setSupplier(supplier[2]);
        
        int quantitySparePart=3;
        
        //Array for car
        Car[] car = new Car[10];
        
        car [0] = new Car ("001","BMW iX xDrive 40", "01-01-2023", 410000, admin[0]);
        car [1] = new Car ("002","BMW iX3 M Sport", "02-02-2023", 322800, admin[1]);
        car [2] = new Car ("003","BMW XM", "05-02-2023", 1398800,admin[2]);
        
        car[0].setSupplier(supplier[0]);
        car[1].setSupplier(supplier[1]);
        car[2].setSupplier(supplier[2]);
        
        int quantityCar=3;  
        
        Order []orderCar = new Order[10];
        int quantityOrderCar=0;
        
        Order []orderSparePart = new Order[10];
        int quantityOrderSparePart=0;
        
        Order overallOrder = new Order();
        
        Payment payment = new Payment();
        Payment paymentLoan = new Payment();
        
        Scanner scanner = new Scanner(System.in);
        
        //Admin and customer login
        
        System.out.println("Clarify you are Admin or Customer (1-Admin, 2-Customer): ");
        int identity = scanner.nextInt();
        
        if (identity==1) {
                scanner.nextLine();
                
            do{    
                System.out.println("Enter admin ID: ");
                String adminID = scanner.nextLine();     
            
                System.out.println("Enter password: ");
                String password = scanner.nextLine();
            
                for (i=0; i<admin.length; i++) {
                
                    if (adminID.equals(admin[i].getAdminID()) && password.equals(admin[i].getPassword())) {
                    
                        System.out.println("\nLOGIN SUCCESSFULLY");
                        admin[i].PrintUserDetails();
                        loginNO=i;
                        identity=3;
                        break;
                    }
                }
                
                if (identity==1){
                    System.out.println("\nWRONG ADMIN ID OR PASSWORD");  
                }
            }while(identity==1); 
        }
        
        else if (identity==2) {
                scanner.nextLine();
                
            do{                   
                System.out.println("Enter customer ID: ");
                String cusID = scanner.nextLine();
            
                System.out.println("Enter password: ");
                String password = scanner.nextLine();
            
                for (i=0; i<customer.length; i++) {
                
                    if (cusID.equals(customer[i].getCustID()) && password.equals(customer[i].getPassword())) {
                    
                        System.out.println("\nLOGIN SUCCESSFULLY");
                        customer[i].PrintUserDetails();
                        loginNO=i;
                        identity=4;
                        break;
                    }
                }
                if (identity==2){
                    System.out.println("\nWRONG CUSTOMER ID OR PASSWORD");

                }
                
            }while(identity==2); 
        }
        
        //Management(Admin)
        if (identity==3){
              
          do{
                System.out.println("\n----------------------------------------------");
                System.out.println("MANAGEMENT BY ADMIN");
                System.out.println("----------------------------------------------");
            
                System.out.println("Option 1 - View car details");
                System.out.println("Option 2 - Add car details");
                System.out.println("Option 3 - Delete car details");
                System.out.println("option 4 - View spare part details");
                System.out.println("Option 5 - Add spare part details");
                System.out.println("Option 6 - Delete spare part details");
         
                System.out.println("\nChoose your option: ");
                option = scanner.nextInt();
            
                switch (option) {
                    case 1: 
                            System.out.println("\n-----------------------------------------------");
                            System.out.println("VIEW CAR DETAILS");
                            System.out.println("-----------------------------------------------");
                            
                            for(int a=0; a<quantityCar;a++){
                                car[a].ViewCarDetails();
                            }
                            
                            option=0;
                        break;
                        
                    case 2: System.out.println("\n-----------------------------------------------");
                            System.out.println("ADD CAR DETAILS");
                            System.out.println("-----------------------------------------------");
                            
                            scanner.nextLine();
                            System.out.println("Enter car ID: ");
                            String carID=scanner.nextLine();
                            
                            System.out.println("Enter car model: ");
                            String model=scanner.nextLine();
                            
                            System.out.println("Enter arrive date: ");
                            String arriveDate=scanner.nextLine();
                        
                            System.out.println("Enter Car price: ");
                            double price=scanner.nextDouble();
                            
                            System.out.println("Car supply by: ");
                            
                            for (int d=0;d<supplier.length;d++){
                                
                                System.out.println("\nNo "+(d+1));
                                supplier[d].printCarSupplier();
                            }
                            
                            System.out.println("\nChoose supplier: ");
                            supplierNo=scanner.nextInt();  
                            
                            supplierNo--;
                            
                            car[quantityCar]= new Car(carID,model,arriveDate, price, admin[loginNO]);
                            car[quantityCar].setSupplier(supplier[supplierNo]);
                            
                            quantityCar++;
                            
                            option=0;
                            
                        break;
                        
                    case 3: System.out.println("\n-----------------------------------------------");
                            System.out.println("DELETE CAR DETAILS");
                            System.out.println("-----------------------------------------------");
                            
                            scanner.nextLine();
                            
                            System.out.println("Enter car ID to be deleted: ");
                            String deleted=scanner.nextLine();
                            
                            for(int b=0;b<quantityCar;b++){
                                
                                 found = car[b].DeleteCarDetails(deleted);
                                 
                                 if (found==1){
                        
                                    for(int c=b;c<quantityCar;c++){
                                    car[b]=car[b+1];
                            
                                    }
                                    quantityCar--;
                                    
                                    carIDExist=0;
                                 }
                                 
                            }
                            
                            if(carIDExist==1){
                                
                                System.out.println("\nCar ID is not exist");
                            }
                            
                            option=0;
                            
                        break;
                        
                    case 4: System.out.println("\n-----------------------------------------------");
                            System.out.println("VIEW SPARE PART DETAILS");
                            System.out.println("-----------------------------------------------");
                            
                            for (int e=0;e<quantitySparePart;e++){
                                
                                System.out.println("No "+(e+1));
                                sparePart[e].printSparePartDetails();   
                                System.out.println("\n");
                            }
                            
                            option=0;
                        break;
                        
                    case 5: System.out.println("\n-----------------------------------------------");
                            System.out.println("Add SPARE PART DETAILS");
                            System.out.println("-----------------------------------------------");
                            
                            scanner.nextLine();
                            
                            System.out.println("Spare part ID: ");
                            String sparePartID=scanner.nextLine();
                            
                            System.out.println("Spare part name: ");
                            String sparePartName=scanner.nextLine();
                            
                            System.out.println("Category: ");
                            String category=scanner.nextLine();
                            
                            System.out.println("Price: ");
                            double sparePartPrice=scanner.nextDouble();
                            
                            for (int d=0;d<supplier.length;d++){
                                
                                System.out.println("\nNo "+(d+1));
                                supplier[d].printCarSupplier();
                            }
                            
                            System.out.println("\nChoose supplier: ");
                            supplierNo=scanner.nextInt();  
                            
                            supplierNo--;
                            sparePart[quantitySparePart]=new SparePart(sparePartID, sparePartName,category, sparePartPrice);
                            
                            sparePart[quantitySparePart].setSupplier(supplier[supplierNo]);
                            
                            quantitySparePart++;
                            
                            option=0;
                            
                         break;
                         
                    case 6: System.out.println("\n-----------------------------------------------");
                            System.out.println("DELETE SPARE PART DETAILS");
                            System.out.println("-----------------------------------------------");
                            
                            System.out.println("Spare part ID to be delete: ");
                            scanner.nextLine();
                            String sparePartDelete=scanner.nextLine();
                            
                            for(int b=0;b<quantitySparePart;b++){
                                
                                 found = sparePart[b].deleteSparePartDetails(sparePartDelete);
                                 
                                 if (found==1){
                        
                                    for(int c=b;c<quantitySparePart;c++){
                                    sparePart[b]=sparePart[b+1];
                            
                                    }
                                    quantitySparePart--;
                                    
                                    sparePartIDExist=0;
                                 }
                                 
                            }
                            
                            if(sparePartIDExist==1){
                                
                                System.out.println("\nSpare part ID is not exist");
                            }
                            
                            option=0;
                            
                            
                        
                        break;

                    default: System.out.println("\nChoose a correct option !");
                            option=0;
                        
                         break;

                }
            }while (option==0);   
        }
        
        else if (identity==4){
            do{
                System.out.println("\n----------------------------------------------");
                System.out.println("MANAGEMENT BY CUSTOMER");
                System.out.println("----------------------------------------------");
            
                System.out.println("Option 1 - View car details");
                System.out.println("Option 2 - View spare part details");
                System.out.println("Option 3 - Add order");
                System.out.println("Option 4 - View order");
                System.out.println("Option 5 - Delete order");
                System.out.println("Option 6 - View the best admin");
                System.out.println("Option 7 - Add payment details");
                System.out.println("Option 8 - View payment details");
                                     
                System.out.println("\nChoose your option: ");
                option = scanner.nextInt();
                
             do{   
                switch (option){
                    
                    case 1:System.out.println("\n-----------------------------------------------");
                           System.out.println("VIEW CAR DETAILS");
                           System.out.println("-----------------------------------------------");
                           
                            for(int a=0; a<quantityCar;a++){
                                System.out.println("No "+(a+1));
                                car[a].ViewCarDetails();
                            }
                            
                            option=0;
                        
                        break;
                        
                    case 2:System.out.println("\n-----------------------------------------------");
                           System.out.println("view spare part details");
                           System.out.println("-----------------------------------------------");
                            
                           for(int a=0; a<quantityCar;a++){
                               System.out.println("No "+(a+1));
                                sparePart[a].printSparePartDetails();
                            }
                           
                           option=0;
                           
                        break;
                        
                    case 3:System.out.println("\n-----------------------------------------------");
                           System.out.println("ADD ORDER");
                           System.out.println("-----------------------------------------------");
                do{            
                           System.out.println("Enter your order (1-Car, 2-Spare part): ");
                           order= scanner.nextInt();
                           
                    switch (order) {
                        case 1: for(int a=0; a<quantityCar;a++){
                                
                                System.out.println("No: " +(a+1));
                                
                                car[a].ViewCarDetails();
                                System.out.println("\n");
                            }                                                        
                               System.out.println("Enter number for car: ");
                               orderCarID=scanner.nextInt();
                               
                               orderCarID--;
                               
                               scanner.nextLine();
                               
                               System.out.println("Enter order date: ");
                               String orderDate=scanner.nextLine();
                               
                               System.out.println("Enter quantity for car: ");
                               int quantityCarOrder=scanner.nextInt();
                               
                               orderCar[quantityOrderCar]= new Order(orderDate,quantityCarOrder,car[orderCarID], customer[loginNO]);
                               
                               quantityOrderCar++;
                               
                               order=1;
                                                            
                            break;
                            
                        case 2:for (int e=0;e<quantitySparePart;e++){
                                
                                System.out.println("No "+(e+1));
                                sparePart[e].printSparePartDetails();   
                                System.out.println("\n");
                            }
                                System.out.println("Enter number for spare part: ");
                                orderSparePartID=scanner.nextInt();
                                
                                orderSparePartID--;
                                
                                scanner.nextLine();
                                
                               System.out.println("Enter order date: ");
                                orderDate=scanner.nextLine();
                               
                               System.out.println("Enter quantity for spare part: ");
                               quantityCarOrder=scanner.nextInt();
                                
                               orderSparePart[quantityOrderSparePart]= new Order(orderDate,quantityCarOrder,sparePart[orderSparePartID], customer[loginNO]);
                                
                               quantityOrderSparePart++;
                               
                               order=2;
                               
                            break;
                        default: System.out.println("Select the option above !");
                                 order=0;
                            break;
                    }
                }while(order==0);
                
                option=4;
                break;
                           
                   case 4: System.out.println("\n-----------------------------------------------");
                           System.out.println("ORDER VIEW");
                           System.out.println("-----------------------------------------------");
                           
                           double totalPriceCar=0.00, totalPriceSparePart=0.00;
                    do{       
                           System.out.println("Enter your order list: (1-Car, 2-Spare part): ");
                           orderList = scanner.nextInt();
                           
                           switch (orderList){
                               
                               
                               case 1: if (quantityOrderCar==0){
                                        
                                        System.out.println("\nNothing in car Order");
                                        
                                        break;
                                        }
                                      

                                        for(int f=0; f<quantityOrderCar; f++){
                                        
                                        System.out.println("NO: " +(f+1));
                                   
                                        orderCar[f].printOrderCar();
                                        
                                        totalPriceCar=totalPriceCar+orderCar[f].calPriceCar();
                                        
                                        orderCar[f].setTotalPriceCar(totalPriceCar);
                                       }

                                       System.out.println("\nTotal price for car: " +orderCar[quantityOrderCar-1].getTotalPriceCar());
                                     
                                       finalPriceCar = orderCar[quantityOrderCar-1].getTotalPriceCar();
                                       
                                       System.out.println("Customer by: "+orderCar[quantityOrderCar-1].getCustomer().getCustID());
                                       
                                       break;
                                       
                               case 2: if (quantityOrderSparePart==0){
                                        
                                        System.out.println("\nNothing in spare part Order");
                                        
                                        break;
                                        }
                                   
                                        for(int f=0; f<quantityOrderSparePart; f++){
                                   
                                        System.out.println("NO: " +(f+1));
                                   
                                        orderSparePart[f].printOrderSparePart();
                                        
                                         totalPriceSparePart=totalPriceSparePart+orderSparePart[f].calPriceSparePart();
                                         
                                         orderSparePart[f].setTotalPriceSparePart(totalPriceSparePart);
                                        
                                       }
                               
                                       System.out.println("\nTotal price for spare part: " +orderSparePart[quantityOrderSparePart-1].getTotalPriceSparePart());
                                       
                                       finalPriceSparePart = orderSparePart[quantityOrderSparePart-1].getTotalPriceSparePart();                                     
                                                                             
                                       System.out.println("Customer by: "+orderSparePart[quantityOrderSparePart-1].getCustomer().getCustID());
                               
                                        break;
                               default: System.out.println("Enter the option above !");
                                        orderList=0;
                                        
                                         break;
                           }
                    }while(orderList==0);
                           
                       option=0;    
                               break;
                               
                   case 5: System.out.println("\n-----------------------------------------------");
                           System.out.println("DELETE ORDER");
                           System.out.println("-----------------------------------------------");         
                           
                        do{
                           System.out.println("Delete your order list: (1-Car, 2-Spare part): ");
                           deleteOrderList = scanner.nextInt();
                           
                           switch (deleteOrderList){
                               
                               case 1: if (quantityOrderCar==0){
                                   
                                        System.out.println("\nOrder car list is empty !");
                                         break;                                                                       
                                        }
                               
                                        System.out.println("Delete your order number: ");
                                        int deleteCarOrder=scanner.nextInt();
                                        
                                        deleteCarOrder--;
                                       
                                        for(int g=deleteCarOrder;g<quantityOrderCar;g++){
                                            orderCar[g]=orderCar[g+1];
                                         }
                                        
                                        quantityOrderCar--;
                                        
                                        break;
                                        
                               case 2:  if (quantityOrderSparePart==0){
                                   
                                        System.out.println("\nOrder spare part list is empty !");
                                        break;                                                                       
                                        }
                               
                                        System.out.println("Delete your order number: ");
                                        int deleteSparePartOrder=scanner.nextInt();
                                        
                                        deleteSparePartOrder--;
                                       
                                        for(int g=deleteSparePartOrder;g<quantityOrderSparePart;g++){
                                            orderSparePart[g]=orderSparePart[g+1];
                                         }
                                        
                                        quantityOrderSparePart--;
                                        
                                        break;
                                        
                               default: System.out.println("Select the option above !");
                               
                                        deleteOrderList=0;
                               break;
 
                           }
                        }while(deleteOrderList==0);
                        
                        option=0;
                        
                        break;
                     
                   case 6: System.out.println("\n-----------------------------------------------");
                           System.out.println("VIEW THE BEST ADMIN");
                           System.out.println("-----------------------------------------------");  
                           
                           double bestAdmin[]={0.00,0.00,0.00};
                           
                           
                           if(quantityOrderCar==0){
                               
                               System.out.println("Empty Order !");
                               
                               option=0;
                               
                               break;
                           }
                           
                           for(int g=0;g<quantityOrderCar;g++){
                               
                               int award=orderCar[g].calAward();
                               
                               if(award==0){
                                   
                                   bestAdmin[0]=bestAdmin[0]+orderCar[g].calPriceCar();
                               }
                               else if(award==1){
                                   
                                   bestAdmin[1]=bestAdmin[1]+orderCar[g].calPriceCar();
                                   
                               }
                               
                               else if(award==2){
                                   
                                   bestAdmin[2]=bestAdmin[2]+orderCar[g].calPriceCar();
                                   
                               }
                               
                               
                           }
                           
                           double highest=bestAdmin[0];
                           
                           index=0;
                           
                           for(int l=0; l<3; l++){
                               
                               if(bestAdmin[l]>highest){
                                   
                                   highest=bestAdmin[l];
                                   index=l;
                               }                                                           
                           }
                                                 
                           
                           Award award = new Award(admin[index],highest);
                                              
                           overallOrder.setAward(award);
                           
                           overallOrder.printAward();

                           option=0;

                      break;  
                      
                   case 7:                
                           System.out.println("\n-----------------------------------------------");
                           System.out.println("ADD PAYMENT DETAILS");
                           System.out.println("-----------------------------------------------");  
                           
                        do{
                           System.out.println("Did you need loan: (1-No, 2-Yes): ");
                           loan = scanner.nextInt();
                           
                           switch (loan){
                               
                               case 1:  
                                        System.out.println("\n-----------------------------------------------");
                                        System.out.println("PAYMENT PART");
                                        System.out.println("-----------------------------------------------");
                                        
                                        scanner.nextLine();
                                        System.out.println("Payment ID: ");
                                        String paymentID = scanner.nextLine();
                                        System.out.println("Payment Method: ");
                                        String paymentMethod = scanner.nextLine();
                                        System.out.println("Payment Date: ");
                                        String datePaid = scanner.nextLine();
                                        
                                        payment = new Payment(paymentID, paymentMethod, datePaid);        
                                                                               
                                        paymentWithoutLoan=1;
                                        
                                        loan=1;
                                        
                                    break;
                                    
                               case 2:  
                                        System.out.println("\n-----------------------------------------------");
                                        System.out.println("PAYMENT PART");
                                        System.out.println("-----------------------------------------------");
                                        
                                        scanner.nextLine();
                                        
                                        System.out.println("Payment ID: ");
                                        paymentID = scanner.nextLine();
                                        System.out.println("Payment Method: ");
                                        paymentMethod = scanner.nextLine();
                                        System.out.println("Payment Date: ");
                                        datePaid= scanner.nextLine();
                                        
                                        System.out.println("\n-----------------------------------------------");
                                        System.out.println("BANK PART");
                                        System.out.println("-----------------------------------------------");
                                        System.out.println("Bank Name:");
                                        String bankName = scanner.nextLine();
                                        System.out.println("Lender Name:");
                                        String lenderName = scanner.nextLine();
                                        System.out.println("Lender Phone Number:");
                                        int lenderPhoneNo = scanner.nextInt();
                                        System.out.println("Lender Salary:");
                                        int lenderSalary = scanner.nextInt();
                                        System.out.println("Lender Account:");
                                        int account = scanner.nextInt(); 
                                        
                                        scanner.nextLine();
                                        
                                        System.out.println("Guarantor Name:");
                                        String guarantor = scanner.nextLine();
                                        System.out.println("Guarantor Phone Number:");
                                        int guarantorPhoneNo = scanner.nextInt();
                                        System.out.println("Loan Amount");
                                        double loanAmount = scanner.nextDouble();
                                        System.out.println("Loan Rate");
                                        double loanRate = scanner.nextDouble();
                                        System.out.println("Loan Period:");
                                        int loanPeriod = scanner.nextInt();
                                        System.out.println("Loan Start Date:");
                                        String startDate = scanner.nextLine();
                                        scanner.nextLine();
                                        
                                        Bank bank= new Bank(bankName, lenderName, lenderPhoneNo, lenderSalary, account, guarantor, guarantorPhoneNo, loanAmount, loanRate, loanPeriod, startDate);                
                                        paymentLoan= new Payment(paymentID, paymentMethod, datePaid, bank);
                                                                              
                                        
                                        paymentWithLoan=1;
                                        
                                        loan=2;
                                        
                                        
                                        
                                    break;
                                        
                               default: System.out.println("Select the option above !");
                               
                                        loan=0;
                               break;
 
                           }
                           
                        }while(loan==0);
                        
                        option=0;           
                       break;    
                       
                   case 8: System.out.println("\n-----------------------------------------------");
                           System.out.println("VIEW PAYMENT DETAIL");
                           System.out.println("-----------------------------------------------");  
                           
                           finalPrice=finalPriceCar+finalPriceSparePart;
                           
                           payment.setFinalPrice(finalPrice);
                           
                           
                           do{
                                System.out.println("Did you have loan: (1-No, 2-Yes): ");
                                viewLoan = scanner.nextInt();
                           
                                switch (viewLoan){
                               
                                    case 1: if(paymentWithoutLoan==0 ){
                                        
                                            System.out.println("Payment details is not exist !");
                                        
                                        break;
                                        
                                             }
                                    
                                            else if(paymentWithoutLoan==1){
                                                
                                               
                                                overallOrder.setPayment(payment);
                                               
                                               
                                                payment.printPaymentDetail();
                                                
                                                System.out.println("\nTotal Price: " +overallOrder.getPayment().getFinalPrice());
                                                 
                                            }

                                        viewLoan = 1;
                                        
                                        break;
                                        
                                    case 2:  if(paymentWithLoan==0 ){
                                        
                                            System.out.println("Payment details is not exist !");
                                        
                                        break;
                                        
                                            }    
                                            
                                            else if(paymentWithLoan==1){
                                                
                                                
                                                overallOrder.setPayment(payment);
                                                
                                                
                                                paymentLoan.printPaymentDetail1();
                                                
                                                System.out.println("\nTotal Price: " +overallOrder.getPayment().getFinalPrice());
                                                 
                                            }


                                        viewLoan =2;
                                
                                        break;
                                        
                               default: System.out.println("Select the option above !");
                               
                                        viewLoan=0;
                                        
                                    break;
 
                                }
                            }while(viewLoan==0);
                        
                        option=0;
                        
                        break;
                                      
                    default: System.out.println("\nChoose a correct option !");
                            
                            option=0;
                            break;       
                }
             }while(option==4);
             
            }while(option==0);
            
        }*/
        
        
        
          
    }
    
    
    
    
    
}