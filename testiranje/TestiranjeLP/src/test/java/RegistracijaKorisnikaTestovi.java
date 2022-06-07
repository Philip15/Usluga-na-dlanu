import java.time.Duration;
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

public class RegistracijaKorisnikaTestovi 
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
    public void beforeMethod()
    {
        driver = Common.initializeDriver(browserType);
        Common.resetDatabase(driver,null);
	driver = Common.disposeDriver(driver);
	driver = Common.initializeDriver(browserType);
        navigate(driver);
    }

    @Test
    @Parameters("sdata")
    public void parametrizedTest(String sdata)
    {
	RegistrationData data = new RegistrationData(sdata);
	System.out.println("Running test "+testNO++ + ": "+data.data);
	navigate(driver);
	RegisterPage page=new RegisterPage(driver);
	page.email.sendKeys(data.email);
	page.firstName.sendKeys(data.firstName);
	page.lastName.sendKeys(data.lastName);
	page.username.sendKeys(data.username);
	page.password.sendKeys(data.password);
        page.reenterpassword.sendKeys(data.reenterpassword);
	if(data.conditions)
	{
	    page.conditions.click();
	}
	page.registerButton.click();
	if(data.errMsg.equals("ok"))
	{
            WebDriverWait wait = new WebDriverWait(driver, Duration.ofSeconds(1));
            Alert alert = wait.until(ExpectedConditions.alertIsPresent());
            Assert.assertEquals(
                alert.getText(),
                "Uspešno ste registrovani!");
	    alert.accept();
	}
        else if(data.errMsg.equals("email"))
	{
            WebDriverWait wait = new WebDriverWait(driver, Duration.ofSeconds(1));
            Assert.assertThrows(TimeoutException.class,()->{wait.until(ExpectedConditions.alertIsPresent());});
	}
	else
	{
	    Assert.assertEquals(
		page.errorText.getText(),
		data.errMsg);
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
    
    public void navigate(WebDriver driver)
    {
        driver.get(Common.baseURL);
	driver.manage().window().setSize(new Dimension(1265, 1000));
        driver.findElement(By.linkText("Registracija")).click();
    }
}
