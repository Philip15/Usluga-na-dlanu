/*
 * Autor: Filip Janjić 2019/0116
 * 
 * 
 */
package Testovi;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;
import org.testng.Assert;
import org.testng.annotations.AfterClass;
import org.testng.annotations.AfterMethod;
import org.testng.annotations.BeforeClass;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;

/**
 *
 * @author Korisnik
 */
public class UredjivanjeProfilaAdmin {
    
    public static String baseUrl = "http://localhost:8080";
    public static WebDriver driver;
    private static String currPassAdmin = "qwerty";
    private static String adminUsername = "lazar";
    private static String adminPass = "qwerty";
    

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
        
        driver.findElement(By.xpath("/html/body/header/nav/div/ul/li[3]/a")).click();
    }
    
    private void logoutAdmin()
    {
        driver.findElement(By.xpath("//*[@id=\"loginModal\"]/div/div/div[2]/form/div[3]/button")).click();
        
        driver.findElement(By.xpath("/html/body/header/nav/div/a/img")).click();
        
        driver.findElement(By.xpath("/html/body/header/nav/div/ul/li[7]/a")).click();
    }
    
    private void izmeniElement(String xpath, String oldVal, String newVal)
    {
        
        WebDriverWait wait0 = new WebDriverWait(driver, 10);
        WebElement userElement;
        WebDriverWait wait = new WebDriverWait(driver, 10);
        WebElement passwordElement;
        
        WebElement pElement = wait.until(ExpectedConditions.elementToBeClickable(By.xpath(xpath)));

        String oldName = pElement.getAttribute("value");
        
        pElement.clear();
        
        if(oldName.equals(oldVal))
        {
            pElement.sendKeys(newVal);
        }
        else
        {
            pElement.sendKeys(oldVal);
        }
        
        driver.findElement(By.xpath("/html/body/div[1]/div/div[2]/div/form[1]/div[5]/button")).click();
        
        wait.until(ExpectedConditions.alertIsPresent());
        
        driver.switchTo().alert().accept();
        
        driver.navigate().refresh();
        
        userElement = wait.until(ExpectedConditions.elementToBeClickable(By.xpath(xpath)));
        userElement.click();
        
        String newName = userElement.getAttribute("value");
        
        if(oldName.equals(oldVal))
        {
            Assert.assertTrue(newName.contains(newVal));
        }
        else
        {
            Assert.assertTrue(newName.contains(oldVal));
        }
    }
    
    private void ocistiElement(String xpath, String poruka, String msgXpath, String btnXpath)
    {
         
        WebDriverWait wait0 = new WebDriverWait(driver, 10);
        WebElement userElement;
        WebDriverWait wait = new WebDriverWait(driver, 10);
        WebElement passwordElement;
        
        WebElement pElement = wait.until(ExpectedConditions.elementToBeClickable(By.xpath(xpath)));

        String oldName = pElement.getAttribute("value");
        
        pElement.clear();

        driver.findElement(By.xpath(btnXpath)).submit();
        
        String msg = driver.findElement(By.xpath(msgXpath)).getText();
        
        pElement = wait.until(ExpectedConditions.elementToBeClickable(By.xpath(xpath)));
        
        String newName = pElement.getAttribute("value");
        
        Assert.assertTrue(newName.equals(oldName) && msg.contains(poruka));
        
    }
    
    private void promeniPass(String stara, String nova, String novaPonovo, String poruka)
    {
        WebDriverWait wait = new WebDriverWait(driver, 10);
        WebElement oldPass = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[2]/div[1]/div/input")));
        WebElement newPass = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[2]/div[2]/div/input")));
        WebElement newPassRepeat = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[2]/div[3]/div/input")));
        
        oldPass.sendKeys(stara);
        newPass.sendKeys(nova);
        newPassRepeat.sendKeys(novaPonovo);
        
        WebElement buttonSubmit = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[2]/div[4]/button")));
        
        buttonSubmit.submit();
        
        String msg = driver.findElement(By.xpath("/html/body/div[1]/div/div[2]/div/form[2]/p")).getText();
        
        Assert.assertTrue(msg.contains(poruka));
    }
    
    /*
    @Test
    public void uspesnaPromenaImena()
    {
        izmeniElement("/html/body/div[1]/div/div[2]/div/form[1]/div[1]/div[1]/input", "Petar", "Lazar");
    }
    
    @Test
    public void uspesnaPromenaPrezimena()
    {
        izmeniElement("/html/body/div[1]/div/div[2]/div/form[1]/div[1]/div[2]/input", "Peric", "Premovic");
    }
    
    @Test
    public void uspesnaPromenaKorisnickogImena()
    {
        izmeniElement("/html/body/div[1]/div/div[2]/div/form[1]/div[2]/div/input", "lazar", "novoKorisnickoIme");
    }
    
    @Test
    public void uspesnaPromenaEmail()
    {
        izmeniElement("/html/body/div[1]/div/div[2]/div/form[1]/div[3]/div/input", "pl190091d@student.etf.bg.ac.rs", "novmejladmin@gmail.com");
    }
    
    
    
    
    @Test
    public void uspesnaPromenaLozinke()
    {
        
        WebDriverWait wait = new WebDriverWait(driver, 10);
        WebElement oldPass = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[2]/div[1]/div/input")));
        WebElement newPass = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[2]/div[2]/div/input")));
        WebElement newPassRepeat = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[2]/div[3]/div/input")));
        
        oldPass.sendKeys("qwerty");
        newPass.sendKeys("novaLozinka");
        newPassRepeat.sendKeys("novaLozinka");
        
        currPassAdmin = "novaLozinka";
        
        WebElement buttonSubmit = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[2]/div[4]/button")));
        
        buttonSubmit.submit();
        
        wait.until(ExpectedConditions.alertIsPresent());
        
        driver.switchTo().alert().accept();
        
        logoutAdmin();
        
        login(adminUsername, currPassAdmin);
        
        Assert.assertNotNull(driver.findElement(By.xpath("/html/body/header/nav/div/a/img")));
        
    }
    
    @Test
    public void neuspesnaIzmenaImenaPrazno()
    {
        ocistiElement("/html/body/div[1]/div/div[2]/div/form[1]/div[1]/div[1]/input", "Obavezna polja ne smeju biti prazna!",
                "/html/body/div[1]/div/div[2]/div/form[1]/p", "/html/body/div[1]/div/div[2]/div/form[1]/div[5]/button");
    }
    
    @Test
    public void neuspesnaIzmenaPrezimenaPrazno()
    {
        ocistiElement("/html/body/div[1]/div/div[2]/div/form[1]/div[1]/div[2]/input", "Obavezna polja ne smeju biti prazna!",
                "/html/body/div[1]/div/div[2]/div/form[1]/p", "/html/body/div[1]/div/div[2]/div/form[1]/div[5]/button");
    }
    
    @Test
    public void neuspesnaIzmenaKorisnickogImenaPrazno()
    {
        ocistiElement("/html/body/div[1]/div/div[2]/div/form[1]/div[2]/div/input", "Obavezna polja ne smeju biti prazna!",
                "/html/body/div[1]/div/div[2]/div/form[1]/p", "/html/body/div[1]/div/div[2]/div/form[1]/div[5]/button");
    }
    
    @Test
    public void neuspesnaIzmenaEmailaPrazno()
    {
        ocistiElement("/html/body/div[1]/div/div[2]/div/form[1]/div[3]/div/input", "Obavezna polja ne smeju biti prazna!",
                "/html/body/div[1]/div/div[2]/div/form[1]/p", "/html/body/div[1]/div/div[2]/div/form[1]/div[5]/button");
    }
    
    @Test
    public void neuspesnaIzmenaLozinkeStaraPrazno()
    {
        promeniPass("", "qwerty", "qwerty", "Niste uneli sva polja!");
    }
    
    @Test
    public void neuspesnaIzmenaLozinkeNovaPrazno()
    {
        promeniPass("qwerty", "", "qwerty", "Niste uneli sva polja!");
    }
    
    @Test
    public void neuspesnaIzmenaLozinkeNovaPonovoPrazno()
    {
        promeniPass("qwerty", "qwerty", "", "Niste uneli sva polja!");
    }
    
    @Test
    public void neuspesnaIzmenaLozinkeStaraNetacna()
    {
        promeniPass("perapera", "qwerty", "qwerty", "Morate uneti ispravnu lozinku!");
    }
    
    @Test
    public void neuspesnaIzmenaLozinkeNisuIste()
    {
        promeniPass("qwerty", "qwerty123", "qwerty12", "Lozinke moraju biti iste!");
    }
    
    @Test
    public void neuspesnaIzmenaKorisnickoImePostoji()
    {
        
        WebDriverWait wait0 = new WebDriverWait(driver, 10);
        WebElement userElement;
        WebDriverWait wait = new WebDriverWait(driver, 10);
        WebElement passwordElement;
        
        WebElement pElement = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[1]/div[2]/div/input")));
        
        pElement.clear();
        String oldName = pElement.getText();
        
        pElement.sendKeys("mika");
        
        driver.findElement(By.xpath("/html/body/div[1]/div/div[2]/div/form[1]/div[5]/button")).click();
        
        String msg = driver.findElement(By.xpath("/html/body/div[1]/div/div[2]/div/form[1]/p")).getText();
        
        driver.navigate().refresh();
        
        pElement = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[1]/div[2]/div/input")));
        
        String newName = pElement.getText();
        
        Assert.assertTrue(msg.contains("Korisničko ime postoji!") && oldName.equals(newName));
        
    }
    
    @Test
    public void neuspesnaIzmenaEmailNeispravan()
    {
        
        WebDriverWait wait = new WebDriverWait(driver, 10);
        WebElement passwordElement;
        
        WebElement pElement = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[1]/div[3]/div/input")));
        
        pElement.clear();
        String oldEmail = pElement.getText();
        
        pElement.sendKeys("novMejl");
        
        driver.findElement(By.xpath("/html/body/div[1]/div/div[2]/div/form[1]/div[5]/button")).click();
        
        String msg = driver.findElement(By.xpath("/html/body/div[1]/div/div[2]/div/form[1]/div[3]/div/input")).getAttribute("validationMessage");

        Assert.assertTrue(msg.contains("Please include an '@' in the email address"));
    }
    
    @Test
    public void neuspesnaIzmenaEmailVecPostoji()
    {
        
        WebDriverWait wait = new WebDriverWait(driver, 10);
        WebElement passwordElement;
        
        WebElement pElement = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[1]/div[3]/div/input")));
        
        pElement.clear();
        String oldEmail = pElement.getText();
        
        pElement.sendKeys("pera@gmail.com");
        
        driver.findElement(By.xpath("/html/body/div[1]/div/div[2]/div/form[1]/div[5]/button")).click();
        
        String msg = driver.findElement(By.xpath("/html/body/div[1]/div/div[2]/div/form[1]/p")).getText();
        
        driver.navigate().refresh();
        
        pElement = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[1]/div[2]/div/input")));
        
        String newEmail = pElement.getText();
        
        Assert.assertTrue(msg.contains("E-mail već postoji!") && oldEmail.equals(newEmail));
        
        
    }*/
    
   
    
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
        login(adminUsername, adminPass);
    }

    @AfterMethod
    public void tearDownMethod() throws Exception {
        
        driver.navigate().refresh();
        WebDriverWait wait = new WebDriverWait(driver, 10);
        WebElement pElement = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[1]/div[2]/div/input")));
        
        pElement.clear();
        pElement.sendKeys("lazar");
        pElement = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[1]/div[5]/button")));
        pElement.submit();
        
        wait.until(ExpectedConditions.alertIsPresent());
        driver.switchTo().alert().accept();
        
        if(!currPassAdmin.equals("qwerty"))
        {
            pElement = wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[1]/div/div[2]/div/form[2]/div[1]/div/input")));
            pElement.clear();
            pElement.sendKeys(currPassAdmin);

            driver.findElement(By.xpath("/html/body/div[1]/div/div[2]/div/form[2]/div[2]/div/input")).clear();
            driver.findElement(By.xpath("/html/body/div[1]/div/div[2]/div/form[2]/div[2]/div/input")).sendKeys("qwerty");

            driver.findElement(By.xpath("/html/body/div[1]/div/div[2]/div/form[2]/div[3]/div/input")).clear();
            driver.findElement(By.xpath("/html/body/div[1]/div/div[2]/div/form[2]/div[3]/div/input")).sendKeys("qwerty");

            driver.findElement(By.xpath("/html/body/div[1]/div/div[2]/div/form[2]/div[4]/button")).submit();
            
            currPassAdmin = "qwerty";
        
        }
        
        if(driver != null) driver.quit();
    }
    
    
}
