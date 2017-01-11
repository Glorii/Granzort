#include <stdio.h>
#include <string.h>
#include <iostream>
#include <vector>
#include <fstream>
#include "serialInterface.h"
using namespace std;
#define TT
ofstream ofile("a.txt", ios::out);
int lastPosL, newPosL, lastPosR, newPosR, delta = 0;
std::vector<int > encoder;
Serial_Interface serialInterface;

void turnD(int dis, float speed)
{
	encoder = serialInterface.getEncoder();
	newPosL = encoder[0];
	newPosR = encoder[1];
 	while(1)
	{
		if(delta >= dis)
			break;
		serialInterface.setMotorSpeed(0.0, 0.3);
		lastPosL = newPosL;
		lastPosR = newPosR;
		encoder = serialInterface.getEncoder();
		newPosL = encoder[0];
		newPosR = encoder[1];
		ofile << newPosL << ", " << newPosR << endl;
		if(speed > 0)
		{
			if(newPosL > lastPosL)
				delta += (newPosL - lastPosL);
			else if(lastPosL > newPosL + 30000)
				delta += (newPosL + 65536 - lastPosL);
		}
		else
		{
			if(newPosR > lastPosR)
				delta += (newPosR - lastPosR);
			else if(lastPosR > newPosR + 30000)
				delta += (newPosR + 65536 - lastPosR);
		}
		
	}
	cout << dis << "--" << delta << endl;
}      

int main()
{
	char *uart_name = (char*)"/dev/ttyUSB0";
	int baudrate = 115200;
	int dis = 4700;
	printf("[main]: start\n");
	if( !serialInterface.start(uart_name, 115200) ) {
		cout << "[main]: Serial Error" << endl;
		exit(0);
	}
	cout << "================" << endl;
	//serialInterface.setMotorDistance(0.5, 0.0);
	sleep(3);
	cout << "start turn" << endl;	
	turnD(dis, 0.3);
	return 0;
}
