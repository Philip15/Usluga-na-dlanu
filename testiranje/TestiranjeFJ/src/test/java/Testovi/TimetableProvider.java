/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Testovi;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;
import org.testng.annotations.AfterClass;
import org.testng.annotations.BeforeClass;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;

/**
 *
 * @author Korisnik
 */
public class TimetableProvider {
    
     public static String baseUrl = "http://localhost:8080";
    public static WebDriver driver;
    private static String currPassProv = "mika123";
    private static String providerUsername = "mika";
    private static String providerPass = "mika123";
    

    private void login(String username, String pass)
    {
        driver.findElement(By.id("loginButton")).click();
        
        WebDriverWait wait0 = new WebDriverWait(driver, 10);
        WebElement userElement = wait0.until(ExpectedConditions.elementToBeClickable(By.id("username")));
        userElement.click();
        userElement.clear();
        userElement.sendKeys(username);
        
        WebDriverWait wait = new WebDriverWait(driver, 10);
        WebElement passwordElement = wait.until(ExpectedConditions.elementToBeClickable(By.id("password")));
        passwordElement.click();
        passwordElement.clear();
        passwordElement.sendKeys(pass);
        
        driver.findElement(By.xpath("//*[@id=\"loginModal\"]/div/div/div[2]/form/div[3]/button")).click();
        
        driver.findElement(By.xpath("/html/body/header/nav/div/a/img")).click();
        
        driver.findElement(By.xpath("/html/body/header/nav/div/ul/li[4]/a")).click();
    }
    
    @Test
    public void uspesnoZakazivanjeTermina()
    {
       WebDriverWait wait = new WebDriverWait(driver, 10);
       wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[3]/div/div/div[2]/div/table/tbody/tr[1]/td[2]"))).click();
       
    }
    
    
    @BeforeClass
    public static void setUpClass() throws Exception {
    }

    @AfterClass
    public static void tearDownClass() throws Exception {
    }

    @BeforeMethod
    public void setUpMethod() throws Exception {
        System.setProperty("webdriver.chrome.driver", "C:\\Users\\Korisnik\\Desktop\\chromedriver.exe");
        
        driver = new ChromeDriver();
        
        driver.get(baseUrl);
        driver.manage().window().maximize();
        login(providerUsername, providerPass);
    }
    
}
