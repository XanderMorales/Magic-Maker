
@REM default

SET EXE_PATH=C:\Users\Owner\Desktop\ppt_converter\lib\pptconvert.exe
SET SRC_PPT=C:\Users\Owner\Desktop\ppt_converter\var\upload\3.ppt
SET DST_FOLDER=C:\Users\Owner\Desktop\ppt_converter\var\output\3
SET WIDTH=960
SET HEIGHT=720
SET DST_TYPE=jpg

"%EXE_PATH%" %DST_FOLDER%, %DST_TYPE%, %WIDTH%, %HEIGHT%, %SRC_PPT%
