import json
import math
import random
import sys
from geopy.geocoders import Nominatim


def system(myUserInfo, myServices, n, hasToBePremium):

    """
    :param myUserInfo: List of the user's information as follows:
        userInfo[0] = age (int)
        userInfo[1] = country (str)
        userInfo[2] = location (str)
        userInfo[3] = Dictionary with categories as key (str), and likeness as value (int) (dict) [update]
        For every service that the user enters and for every service that the user sends a mssg update DB
        {service1: 10, service2: 21, service3: 25}
    :param myServices: A list of all the services, where each service is a list. (list) (list)
        List containing the service's information:
            [0] = category (str)
            [1] = country (str)
            [2] = rating (double) [update]
            [3] = A list containing integers, each position in the list corresponds to an age group (list) [update]
            [4] = isPremium (bool)
            [5] = popularity (double) [update]
            [6] = longitude (double)
            [7] = latitude (double)
            [8] = id (int)
    :param n: Number of services to be returned
    :param hasToBePremium: If true only returns premium users(bool)

    :return: A list containing an n number of serviceId (list)
    """

    # Variable Declaration
    dictOfServices = {}
    # Define weights
    w = [.2, .1, .55, .05, .1]
    # where:
    #   w[0] --> rating
    #   w[1] --> popularity
    #   w[2] --> likeness
    #   w[3] --> age
    #   w[4] --> distance
    R = 6371
    g = Nominatim(user_agent="Tasker")
    userLat = 0
    userLon = 0
    topCategories = sorted(myUserInfo[3], key=myUserInfo[3].get, reverse=True)[:4]
    for i in range(0, len(topCategories)):
        topCategories[i] = topCategories[i].replace(" ", "")
    if myUserInfo[2] != "N.A":
        userLocation = g.geocode(myUserInfo[2])
        if userLocation:
            userLat = math.radians(userLocation.latitude)
            userLon = math.radians(userLocation.longitude)

    for myService in myServices:
        if hasToBePremium and int(myService[4]) == 0:
            continue

        # Handling country parameter
        if (myUserInfo[1] == myService[1] or myService[1] == "N.A") and (float(myService[2]) >= 3 or (int(myService[4]) == 1) or float(myService[2]) == 0) and myService[0] in topCategories:
            p = 0.0

            # Handling rating, popularity and category parameter
            if myService[2] == 0:
                p += (float(myService[5]) * w[1]) + myUserInfo[3][myService[0]] * w[2]
            else:
                p += (float(myService[2]) * w[0]) + (float(myService[5]) * w[1]) + float(myUserInfo[3][myService[0]]) * w[2]
                
           
            # Handling age parameter
            
            if myUserInfo[0] <= 20:
                p += float(myService[3][0]) * w[3]
            elif myUserInfo[0] <= 30:
                p += float(myService[3][1]) * w[3]
            elif myUserInfo[0] <= 40:
                p += float(myService[3][2]) * w[3]
            elif myUserInfo[0] <= 50:
                p += float(myService[3][3]) * w[3]
            elif myUserInfo[0] <= 60:
                p += float(myService[3][4]) * w[3]
            else:
                p += float(myService[3][5]) * w[3]
           
            
            # Handle location parameter here
            if float(myService[6]) != 0 and myUserInfo[2] != "N.A":
                dLon = float(myService[6]) - userLon
                dLat = float(myService[7]) - userLat

                a = math.sin(dLat / 2) ** 2 + math.cos(userLat) * math.cos(float(myService[7])) * math.sin(dLon / 2) ** 2
                p = (p * w[4]) / R * (2 * math.atan2(math.sqrt(a), math.sqrt(1 - a)))

            # Handle isPremium parameter here
            if int(myService[4]) == 1:
                p *= 2.5   
            dictOfServices[int(myService[8])] = p
    count = 0
    if(int(n) > len(dictOfServices)):
        n = len(dictOfServices)
    while True:
        listOfRecommendations = random.choices(list(dictOfServices.keys()), list(dictOfServices.values()), k=int(n))
        if len(listOfRecommendations) == len(set(listOfRecommendations)):
            return listOfRecommendations
        count += 1
        if count == 100:
            return "error"
    
def recommendCategories(myUserInfo, n):
    ans = []
    topCategories = sorted(myUserInfo, key=myUserInfo.get, reverse=True)[:n]
    for i in range(0, n):
        ans.append(topCategories[i].replace(" ", ""))
    return ans

if __name__ == "__main__":

    """
    likeness --> +1 to each category visited and +10 to each purchase made in that category
    popularity --> +0.05 to each unique visitor, +0.01 to each non-unique visitor, +0.5 to each purchase 
    """

    # Stress test rSystem
    if(len(sys.argv) == 1):

        # Creating dummy data

        # Variable Declaration
        categories = ["Carpentry", "Food", "Painting", "Space"]
        cLon = [35.8216, 35.8989, 35.8881, 35.9110, 35.8412]
        cLat = [14.4811, 14.5146, 14.4048, 14.5029, 14.5393]
        count = 0

        # Creating dummy user
        userInfo = [random.randint(16, 100), "Malta",
                    random.choice(["Zurrieq", "Valletta", " Mdina", "Sliema", "Marsaxlokk"])]

        for i in categories:
            if bool(random.getrandbits):
                userLikes[i] = random.randint(1, 100)
        userInfo.append(userLikes)

        # Creating dummy services
        services = []
        for i in range(0, 100):
            service = [random.choice(categories), "Malta", random.uniform(0, 5),
                    [random.randint(1, 100), random.randint(1, 100), random.randint(1, 100), random.randint(1, 100),
                        random.randint(1, 100), random.randint(1, 100)], random.randint(0, 1),
                    random.uniform(0, 1000)]
            r = random.randint(0, len(cLat) - 1)
            service.append(cLon[r])
            service.append(cLat[r])
            service.append(count)
            count += 1
            services.append(service)
        ans = system(userInfo, services, 10)
        print("Our user:")
        print(userInfo)
        print("Generated ids:")
        print(ans)

        print("Services recommended")
        for i in ans:
            print(services[i])
    else:
        try: 
            if(len(sys.argv) == 3):
                print(recommendCategories({i.split(':')[0]: i.split(':')[1] for i in sys.argv[1].split(',')}, int(sys.argv[2])))
            else:   
                services = []
                current = []
                currentAgeGroup = []
                firstAppend = True
                inAgeGroups = False

                for value in sys.argv[5].split(','):
                    if value != ']':
                        if '[' in value:
                            if not firstAppend:
                                currentAgeGroup.append(value[1:])
                                inAgeGroups = True
                            else:
                                current.append(value[1:])
                                firstAppend = False
                        else:
                            if inAgeGroups:
                                if ']' in value:
                                    currentAgeGroup.append(value[:-1])
                                    inAgeGroups = False
                                    current.append(currentAgeGroup)
                                    currentAgeGroup = []
                                else:    
                                    currentAgeGroup.append(value)
                            elif ']' in value:
                                current.append(value.replace(']', ''))
                                services.append(current)
                                current = []
                                firstAppend = True
                            else:
                                current.append(value)

                userInfo = {i.split(':')[0]: i.split(':')[1] for i in sys.argv[4].split(',')}
                for i in userInfo.copy():
                    if i.startswith(" "):
                        userInfo[i.strip()] = userInfo.pop(i)
                print(system([int(sys.argv[1]), sys.argv[2], sys.argv[3], userInfo], services, sys.argv[6], sys.argv[7] == "True"))
        except  Exception as e:
            print("An Internal Error has occured")
            print(e)
        
