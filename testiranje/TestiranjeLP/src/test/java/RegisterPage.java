import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class RegisterPage 
{
    private WebDriver driver;

    @FindBy(name="email")
    WebElement email;
    
    @FindBy(name="ime")
    WebElement firstName;

    @FindBy(name="prezime")
    WebElement lastName;
    
    @FindBy(css="body > div.text-center.d-flex.justify-content-center.align-items-center.mt-5 > div > form > div:nth-child(5) > input")
    WebElement username;
    
    @FindBy(css="body > div.text-center.d-flex.justify-content-center.align-items-center.mt-5 > div > form > div:nth-child(6) > input")
    WebElement password;

    @FindBy(name="password2")
    WebElement reenterpassword;

    @FindBy(name="uslovi_koriscenja")
    WebElement conditions;
    
    @FindBy(css = "body > div.text-center.d-flex.justify-content-center.align-items-center.mt-5 > div > form > div:nth-child(9) > button")
    WebElement registerButton;
    
    @FindBy(className = "text-danger")
    WebElement errorText;

    public RegisterPage(WebDriver driver)
    {
	this.driver=driver;
	PageFactory.initElements(driver, this);
    }
}
