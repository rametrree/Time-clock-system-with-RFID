#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>

// Define RFID pin connections
#define RST_PIN D3
#define SS_PIN D4

MFRC522 mfrc522(SS_PIN, RST_PIN);  // Create an instance of the MFRC522 class

// Set up Wi-Fi credentials
const char* ssid = "fill your ssid wifi";  // Enter your Wi-Fi SSID
const char* password = "fill your password wifi";  // Enter your Wi-Fi password

// Set the server URL for the PHP script
const char* serverName = "http://IP/record.php";  // Replace with the server IP (e.g., XAMPP)

WiFiClient client;  // Create WiFi client
HTTPClient http;  // Create HTTP client

void setup() {
  Serial.begin(115200);  // Initialize serial communication for debugging
  SPI.begin();  // Initialize SPI bus
  mfrc522.PCD_Init();  // Initialize the MFRC522 RFID reader
  WiFi.begin(ssid, password);  // Connect to Wi-Fi network

  // Attempt to connect to Wi-Fi
  Serial.print("Connecting to WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");  // Print dots while connecting
  }
  Serial.println("\nWiFi Connected!");  // Print a message when connected
}

void loop() {
  // Check if a new RFID card is present
  if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial()) {
    delay(500);  // Wait for 500ms before checking again
    return;  // Exit if no card is present
  }

  // Read the UID from the RFID card
  String uid = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
      uid += String(mfrc522.uid.uidByte[i], HEX);  // Concatenate the UID bytes as a hexadecimal string
  }
  uid.toUpperCase();  // Convert the UID to uppercase
  uid.replace(" ", "");  // Remove spaces from the UID string
  Serial.println("UID: " + uid);  // Print the UID to the serial monitor

  // Send the UID to the PHP script if Wi-Fi is connected
  if (WiFi.status() == WL_CONNECTED) {
    http.begin(client, serverName);  // Initialize the HTTP request
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");  // Set content type to form data
    String httpRequestData = "rfid_uid=" + uid;  // Prepare the data to send (UID)
    
    // Send the HTTP POST request
    int httpResponseCode = http.POST(httpRequestData);
    
    // Check if the request was successful
    if (httpResponseCode > 0) {
      String response = http.getString();  // Get the response from the server
      Serial.println(response);  // Print the response to the serial monitor
    } else {
      Serial.println("Error in HTTP request");  // Print error message if the request failed
    }

    http.end();  // End the HTTP request
  }

  delay(5000);  // Wait for 5 seconds before checking for a new card again
}
