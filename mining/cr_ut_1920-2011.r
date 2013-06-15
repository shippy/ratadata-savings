# Umrtnostni tabulky (CSU: http://www.czso.cz/csu/redakce.nsf/i/umrtnostni_tabulky_za_cr_od_roku_1920/$File/cr_ut_1920_2011.zip)
yrs = 1920:2011
folder = "/Users/Simon/Coding/Ratadata/ratadata-savings/cr_ut_1920_2011/" # change path
genders = c("M", "Z")
ut <
# Loads data for all years outside of 1938-1944
for (i in 1:length(yrs)) {
  for (g in 1:length(genders)) {
    yr <- yrs[i]
    gender <- genders[g]
    filename <- paste(folder, "UT", yr, gender, ".XLS", sep="")
    if (file.exists(filename)) {
      data <- read.xls(filename, skip=2)
      # Save in some decent manner
    }
  }
}