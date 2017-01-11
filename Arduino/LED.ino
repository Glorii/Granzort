#include<FastLED.h>
#define NUM_LEDS 144
#define DATA_PIN 6
#define BRIGHTNESS 80
#define WHITE 1
#define RED 2
#define ORANGE 3
#define YELLOW 4
#define GREEN 5
#define CYAN 6
#define BLUE 7
#define PURPLE 8
CRGB leds[NUM_LEDS];
  
String usbnumber = ""; //this variable holds what we are currently reading from seri
void setup() {
  FastLED.addLeds<NEOPIXEL, DATA_PIN>(leds, NUM_LEDS);
  FastLED.setBrightness(BRIGHTNESS);
  Serial.begin(9600);
}

void loop() {
    usbnumber="";
    while(Serial.available() > 0){//if there is anything on the serial port, read it
      usbnumber+=char(Serial.read());
      delay(2);
    }
    Serial.flush();
    if(usbnumber.length()!=0)
    {
      Serial.println(usbnumber);
    }
    mode = usbnumber[0];
    for(int i=1; i<usbnumber.length(); i++){
      int index = i%NUM_LEDS;
      if(index==0){
        FastLED.show();
        delay(20);
      }
      switch(usbnumber[i]){
        case WHITE:leds[index] = CRGB::White;break;
        case RED:leds[index] = CRGB::Red;break;
        case ORANGE:leds[index] = CRGB::Orange;break;
        case YELLOW:leds[index] = CRGB::Yellow;break;
        case GREEN:leds[index] = CRGB::Green;break;
        case CYAN:leds[index] = CRGB::Cyan;break;
        case BLUE:leds[index] = CRGB::Blue;break;
        case PURPLE:leds[index] = CRGB::Purple;break;
      }
    }
    callDarkness();
}

void callDarkness(){
  for(int i=0; i<NUM_LEDS; i++){
    leds[i] = CRGB::Black;    
  }
  FastLED.show();
}

