import processing.serial.*;

Serial port;

int in_data;
int pre_value;
String month;
void setup() {
  month = (int(month())<10)?"0"+str(month()):str(month());
  size(300, 300);
  port = new Serial(this, Serial.list()[0], 9600);
  background(0);
}

void draw() {
  if (port.available()>=3) {
    if (port.read()=='H') {
      int high = port.read();
      int low = port.read();
      int value = high*256+low;
      println(value);
      if (pre_value-value > 10) {
        String[] line = loadStrings("http://watanabe.nkmr.io/WebP/final/chat_insert.php?group_id=1&message=ご飯食べたよ&date="+str(year())+"-"+month+"-"+str(day()));
      }
      if (value <2000) {
        String[] lines = loadStrings("http://ryota.nkmr.io/php/wp_insert.php?gram="+value+"&group_id=1");
        pre_value = value;
      }
    }
  }
}
