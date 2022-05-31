package com.example.galeria;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.util.DisplayMetrics;

public class Utils {

    public static int calculateNumberOfColumns(Context context, float columnWidth){
        DisplayMetrics displayMetrics = context.getResources().getDisplayMetrics();
        float screenWidth = displayMetrics.widthPixels;
        return (int) (screenWidth/columnWidth+0.5);
    }

    public static Bitmap getScaleBitmap(String imagePath, int w, int h){

        //Descobre o tamanho original da imagem
        BitmapFactory.Options bmOptions = new BitmapFactory.Options();
        bmOptions.inJustDecodeBounds = true;
        BitmapFactory.decodeFile(imagePath, bmOptions);

        int photoW = bmOptions.outWidth;
        int photoH = bmOptions.outHeight;

        //Calcula um fator de escala
        int scaleFactor = Math.max(photoW/w, photoH/h);

        //Decodifica e atribui o fator de escala
        bmOptions.inJustDecodeBounds = false;
        bmOptions.inSampleSize = scaleFactor;

        return BitmapFactory.decodeFile(imagePath, bmOptions);
    }

    public static Bitmap getBitmap(String imagePath){
        BitmapFactory.Options bmOptions = new BitmapFactory.Options();
        bmOptions.inJustDecodeBounds = false;
        return BitmapFactory.decodeFile(imagePath, bmOptions);
    }
}
