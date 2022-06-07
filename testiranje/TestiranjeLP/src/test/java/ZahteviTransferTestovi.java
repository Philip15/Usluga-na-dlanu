import java.time.Duration;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import org.openqa.selenium.Dimension;
import org.openqa.selenium.TimeoutException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;
import org.testng.Assert;
import org.testng.annotations.AfterMethod;
import org.testng.annotations.AfterSuite;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.BeforeSuite;
import org.testng.annotations.Optional;
import org.testng.annotations.Parameters;
import org.testng.annotations.Test;

public class ZahteviTransferTestovi
{   
    private static WebDriver driver;
    private static String browserType;
    private static int testNO=1;

    @BeforeSuite
    @Parameters("browser")
    public void beforeSuite(@Optional("chrome") String browser)
    {
	browserType = browser;
	Common.setupDriver(browser);
    }

    @BeforeMethod
    @Parameters("startState")
    public void beforeMethod(int state)
    {
        driver = Common.initializeDriver(browserType);
        String customSql=generateRequest(state);
        Common.resetDatabase(driver,customSql);
	driver = Common.disposeDriver(driver);
	driver = Common.initializeDriver(browserType);
    }

    @Test
    @Parameters({"startState","endState","buttonIndex","transferType"})
    public void transferTest(int state,int endState,int buttonIndex,boolean transferType)
    {
	System.out.println("Running transferTest "+testNO++ + ": "+state+" "+endState+" "+buttonIndex);
        if(transferType)
        {
            navigateUser(driver);
            for (int i = 0; i < 9; i++) 
            {
                int pos = positionMapUser(i);
                if(pos!=-1)
                {
                    if(i==state)
                    {
                        AssertRequestExists(driver, pos, "test zahtev",false);
                    }
                    else
                    {
                        AssertRequestNotExists(driver, pos);
                    }
                }
            }
            navigateProvider(driver);
            for (int i = 0; i < 9; i++) 
            {
                int pos = positionMapProvider(i);
                if(pos!=-1)
                {
                    if(i==state)
                    {
                        AssertRequestExists(driver, pos+1, "test zahtev",true);
                    }
                    else
                    {
                        AssertRequestNotExists(driver, pos+1);
                    }
                }
            }
        }
        else
        {
            navigateProvider(driver);
            for (int i = 0; i < 9; i++) 
            {
                int pos = positionMapProvider(i);
                if(pos!=-1)
                {
                    if(i==state)
                    {
                        AssertRequestExists(driver, pos+1, "test zahtev",true);
                    }
                    else
                    {
                        AssertRequestNotExists(driver, pos+1);
                    }
                }
            }
            navigateUser(driver);
            for (int i = 0; i < 9; i++) 
            {
                int pos = positionMapUser(i);
                if(pos!=-1)
                {
                    if(i==state)
                    {
                        AssertRequestExists(driver, pos, "test zahtev",false);
                    }
                    else
                    {
                        AssertRequestNotExists(driver, pos);
                    }
                }
            }
        }
        
        
        if(state==1 && endState==2)
        {
            if(buttonIndex==0)
            {
                driver.findElement(By.id("99")).findElement(By.cssSelector("div.d-flex.mt-3.justify-content-end > button")).click();
                driver.findElement(By.name("priceVal")).sendKeys("53729");
                driver.findElement(By.name("offerDesc")).sendKeys("tekst opisa");
                driver.findElement(By.cssSelector("#newOfferModal > div > div > div.modal-body > form > div.d-flex.mt-3.justify-content-end > button")).click();
            }
            else if(buttonIndex==2)
            {
                driver.findElement(By.id("99")).findElement(By.cssSelector("div.d-flex.mt-3.justify-content-end > button")).click();
                driver.findElement(By.name("offerDesc")).sendKeys("tekst opisa");
                driver.findElement(By.cssSelector("#newOfferModal > div > div > div.modal-body > form > div.d-flex.mt-3.justify-content-end > button")).click();
                try {
                    Thread.sleep(1000);
                } catch (InterruptedException ex) {
                    Logger.getLogger(ZahteviTransferTestovi.class.getName()).log(Level.SEVERE, null, ex);
                }
                Assert.assertEquals(
                    driver.findElement(By.id("priceError")).getText(),
                    "Cena mora biti uneta!");
                endState=1;
            }
            else if(buttonIndex==3)
            {
                driver.findElement(By.id("99")).findElement(By.cssSelector("div.d-flex.mt-3.justify-content-end > button")).click();
                driver.findElement(By.name("priceVal")).sendKeys("53729");
                driver.findElement(By.cssSelector("#newOfferModal > div > div > div.modal-body > form > div.d-flex.mt-3.justify-content-end > button")).click();
            }
            else if(buttonIndex==4)
            {
                driver.findElement(By.id("99")).findElement(By.cssSelector("div.d-flex.mt-3.justify-content-end > button")).click();
                driver.findElement(By.name("priceVal")).sendKeys("asd");
                driver.findElement(By.name("offerDesc")).sendKeys("tekst opisa");
                driver.findElement(By.cssSelector("#newOfferModal > div > div > div.modal-body > form > div.d-flex.mt-3.justify-content-end > button")).click();
                try {
                    Thread.sleep(1000);
                } catch (InterruptedException ex) {
                    Logger.getLogger(ZahteviTransferTestovi.class.getName()).log(Level.SEVERE, null, ex);
                }
                Assert.assertEquals(
                    driver.findElement(By.id("priceError")).getText(),
                    "Cena mora biti uneta!");
                endState=1;
            }
        }
        else if(endState==8)
        {
            if(buttonIndex==0)
            {
                driver.findElement(By.id("99")).findElement(By.cssSelector("div.d-flex.mt-3.justify-content-end > a.btn.btn-primary.mx-1")).click();
            }
        }
        else
        {
            if(buttonIndex==0)
            {
                driver.findElement(By.id("99")).findElement(By.cssSelector("div.d-flex.mt-3.justify-content-end > a.btn.btn-success.mx-1")).click();
            }
            else
            {
                driver.findElement(By.id("99")).findElement(By.cssSelector("div.d-flex.mt-3.justify-content-end > a.btn.btn-danger.mx-1")).click();
            }
        }
        
        navigateUser(driver);
        for (int i = 0; i < 9; i++) 
        {
            int pos = positionMapUser(i);
            if(pos!=-1)
            {
                if(i==endState)
                {
                    if(endState==2 && buttonIndex==0)
                    {
                        AssertRequestExists(driver, pos, "test zahtev","53729","tekst opisa");
                    }
                    AssertRequestExists(driver, pos, "test zahtev",false);
                }
                else
                {
                    AssertRequestNotExists(driver, pos);
                }
            }
        }
        navigateProvider(driver);
        for (int i = 0; i < 9; i++) 
        {
            int pos = positionMapProvider(i);
            if(pos!=-1)
            {
                if(i==endState)
                {
                    if(endState==2 && buttonIndex==0)
                    {
                        AssertRequestExists(driver, pos+1, "test zahtev","53729","tekst opisa");
                    }
                    AssertRequestExists(driver, pos+1, "test zahtev",true);
                }
                else
                {
                    AssertRequestNotExists(driver, pos+1);
                }
            }
        }
    }
    
    @AfterMethod
    public void afterMethod()
    {
	driver = Common.disposeDriver(driver);
    }

    @AfterSuite
    public void afterSuite()
    {
	
    }
    
    public String generateRequest(int state)
    {
        String res = "INSERT INTO `korisnici` (`idKorisnika`, `korisnickoIme`, `lozinka`, `email`, `ime`, `prezime`, `profilnaSlika`, `opis`, `pruzalac`, `adresa`, `lat`, `lon`, `idKategorije`, `administrator`) VALUES ('99', 'tasha', '$2y$10$eEmIhXlzMtjuPX9k4BsfkO1dlNKDc9kT2fQP8c.5a2i5YUcownR.i', 'tasha@tasha.tasha', 'tasha', 'tasha', NULL, NULL, '0', NULL, NULL, NULL, NULL, '0');";
        if(state!=0)
        {
            res+="INSERT INTO `zahtevi` (`idZahteva`, `idKorisnika`, `idPruzaoca`, `stanje`, `opis`, `hitno`, `cena`, `komentar`, `ocena`, `recenzija`) VALUES ('99', '99', '10', '"+state+"', 'test zahtev', '0', NULL, NULL, NULL, NULL);";
            if(state!=6 && state!=7 && state!=8)
            {
                res+="INSERT INTO `termini` (`idTermina`, `idPruzaoca`, `datumVremePocetka`, `trajanje`, `idZahteva`) VALUES ('99', '10', '2022-06-30 15:00:00', '60', 99);";
            }
        }
        return res;
    }
    
    public void navigateUser(WebDriver driver)
    {
        Common.login(driver, "tasha", "tasha123");
        driver.findElement(By.cssSelector(".rounded-circle:nth-child(1)")).click();
        driver.findElement(By.cssSelector("body > header > nav > div > ul > li:nth-child(1) > a")).click();
        Assert.assertEquals(
	    driver.getCurrentUrl(),
	    Common.baseURL+"/UserController/requests");
	System.out.println("Navigated to accountRequests");
    }
    
    public void navigateProvider(WebDriver driver)
    {
        Common.login(driver, "mirko", "mirko123");
        driver.findElement(By.cssSelector(".rounded-circle:nth-child(1)")).click();
        driver.findElement(By.cssSelector("body > header > nav > div > ul > li:nth-child(3) > a")).click();
        Assert.assertEquals(
	    driver.getCurrentUrl(),
	    Common.baseURL+"/ProviderController/requests");
	System.out.println("Navigated to accountRequests");
    }
    
    public int positionMapUser(int state)
    {
        switch (state)
        {
            case 1 : return 1;
            case 2 : return 2;
            case 3 : return 3;
            case 6 : return 4;
            default: return -1;
        }
    }
    
    public int positionMapProvider(int state)
    {
        switch (state)
        {
            case 1 : return 1;
            case 2 : return 2;
            case 3 : return 3;
            case 7 : return 4;
            default: return -1;
        }
    }
    
    public void AssertRequestExists(WebDriver driver,int pos,String desc,String price,String opis)
    {
        Assert.assertEquals(
            driver.findElement(By.cssSelector("body > div.container > div:nth-child("+(pos*2)+") > div > p:nth-child(5)")).getText(),
            "Opis: "+desc);
        Assert.assertEquals(
            driver.findElement(By.cssSelector("body > div.container > div:nth-child("+(pos*2)+") > div > p:nth-child(7)")).getText(),
            "Cena: "+price);
        Assert.assertEquals(
            driver.findElement(By.cssSelector("body > div.container > div:nth-child("+(pos*2)+") > div > p:nth-child(8)")).getText(),
            "Komentar: "+opis);
    }
    
    public void AssertRequestExists(WebDriver driver,int pos,String desc,boolean provider)
    {
        if((pos==4 && !provider) || pos==5)
        {
            Assert.assertEquals(
            driver.findElement(By.cssSelector("body > div.container > div:nth-child("+(pos*2)+") > div > p:nth-child(3)")).getText(),
            "Opis: "+desc);
        }
        else
        {
            Assert.assertEquals(
            driver.findElement(By.cssSelector("body > div.container > div:nth-child("+(pos*2)+") > div > p:nth-child(5)")).getText(),
            "Opis: "+desc);
        }
    }
    
    public void AssertRequestNotExists(WebDriver driver,int pos)
    {
        Assert.assertTrue(driver.findElements(By.cssSelector("body > div.container > div:nth-child("+(pos*2)+") > div > p:nth-child(5)")).isEmpty());
        Assert.assertEquals(
            driver.findElement(By.cssSelector("body > div.container > div:nth-child("+(pos*2)+") > h5")).getText(),
            "Trenutno nema zahteva.");
    }
}
