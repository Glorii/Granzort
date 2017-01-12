#include<FastLED.h>
FASTLED_USING_NAMESPACE
#define NUM_LEDS 144
#define FRAMES 192
#define DATA_PIN 6
#define BRIGHTNESS 80
#define FRAMES_PER_SECOND  100

#define WHITE 1
#define RED 2
#define ORANGE 3
#define YELLOW 4
#define GREEN 5
#define CYAN 6
#define BLUE 7
#define PURPLE 8
CRGB leds[NUM_LEDS];
  
void setup() {
  delay(3000);
  FastLED.addLeds<NEOPIXEL, DATA_PIN>(leds, NUM_LEDS);
  FastLED.setBrightness(BRIGHTNESS);
  Serial.begin(9600);
}
String usbnumber = ""; //this variable holds what we are currently reading from seri
int period = 0, mode, bg;

uint8_t gHue = 0; // rotating "base color" used by many of the patterns
uint8_t rowIndex = 0; //bitmap[rowIndex]

void loop() {
    usbnumber="";
    while(Serial.available() > 0){//if there is anything on the serial port, read it
      usbnumber+=char(Serial.read());
//      delay(2);
    }
    Serial.flush();
    if(usbnumber.length()!=0)
    {
      Serial.println(usbnumber);
    }
    mode = usbnumber[0];//the first bit is for mode number
    bg = usbnumber[1];//the second bit is for background number
    if(mode==5){
      painting2(bg);
    }else{
      painting1(bg);
    }
    FastLED.show();
    // insert a delay to keep the framerate modest
    FastLED.delay(1000/FRAMES_PER_SECOND); 
    // do some periodic updates
    EVERY_N_MILLISECONDS( 10 ) { 
      gHue++; 
      if(mode == 5 && period > 2){
        rowIndex++; 
      }else if(period > 1){
        rowIndex++;
      }
    } // slowly cycle the "base color" through the rainbow
    EVERY_N_SECONDS( 2 ) { period++; } // change patterns periodically
    
}


void painting1(int bg)
{
  if(period == 0||period == 3){
    switch(bg){
      case 0:rainbowWithGlitter();break;
      case 1:sinelon();break;
      case 2:juggle();break;
    }
  }else{
    if(index<FRAMES){
      paintMapByRowIndex();
    }else{
      callDarkness();
    }
  }
}
void painting2(int bg)
{
  if(period < 2){
    switch(bg){
      case 0:rainbowWithGlitter();break;
      case 1:sinelon();break;
      case 2:juggle();break;
    }
  }else{
    if(index<FRAMES){
      paintMapByRowIndex();
    }else{
      callDarkness();
    }
  }
}

void paintMapByRowIndex(){
    for(int i=2; i < (rowIndex + 1) * NUM_LEDS + 2; i++){
      int index = i % NUM_LEDS - 2;
      switch(usbnumber[i]){
        case WHITE:leds[index] = CRGB::White;break;
        case RED:leds[index] = CRGB::Red;break;
        case ORANGE:leds[index] = CRGB::Orange;break;
        case YELLOW:leds[index] = CRGB::Yellow;break;
        case GREEN:leds[index] = CRGB::Green;break;
        case CYAN:leds[index] = CRGB::Cyan;break;
        case BLUE:leds[index] = CRGB::Blue;break;
        case PURPLE:leds[index] = CRGB::Purple;break;
        default: leds[index] = CRGB::Black;
      }
    }
}
void callDarkness(){
  for(int i=0; i<NUM_LEDS; i++){
    leds[i] = CRGB::Black;    
  }
  FastLED.show();
}

void rainbow() 
{
  // FastLED's built-in rainbow generator
  fill_rainbow( leds, NUM_LEDS, gHue, 7);
}

void rainbowWithGlitter() 
{
  // built-in FastLED rainbow, plus some random sparkly glitter
  rainbow();
  addGlitter(80);
}

void addGlitter( fract8 chanceOfGlitter) 
{
  if( random8() < chanceOfGlitter) {
    leds[ random16(NUM_LEDS) ] += CRGB::White;
  }
}

void confetti() 
{
  // random colored speckles that blink in and fade smoothly
  fadeToBlackBy( leds, NUM_LEDS, 10);
  int pos = random16(NUM_LEDS);
  leds[pos] += CHSV( gHue + random8(64), 200, 255);
}

void sinelon()
{
  // a colored dot sweeping back and forth, with fading trails
  fadeToBlackBy( leds, NUM_LEDS, 20);
  int pos = beatsin16(13,0,NUM_LEDS);
  leds[pos] += CHSV( gHue, 255, 192);
}

void bpm()
{
  // colored stripes pulsing at a defined Beats-Per-Minute (BPM)
  uint8_t BeatsPerMinute = 62;
  CRGBPalette16 palette = PartyColors_p;
  uint8_t beat = beatsin8( BeatsPerMinute, 64, 255);
  for( int i = 0; i < NUM_LEDS; i++) { //9948
    leds[i] = ColorFromPalette(palette, gHue+(i*2), beat-gHue+(i*10));
  }
}

void juggle() {
  // eight colored dots, weaving in and out of sync with each other
  fadeToBlackBy( leds, NUM_LEDS, 20);
  byte dothue = 0;
  for( int i = 0; i < 8; i++) {
    leds[beatsin16(i+7,0,NUM_LEDS)] |= CHSV(dothue, 200, 255);
    dothue += 32;
  }
}

