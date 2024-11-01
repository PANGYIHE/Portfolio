using System.Collections;
using System.Collections.Generic;
using TMPro;
using UnityEngine;
using UnityEngine.AI;
using UnityEngine.UI;

public class PlayerController : MonoBehaviour
{
    public float turnspeed = 90f, jumpForce = 300f;
    private bool isGrounded;
    public float groundCheckDistance = 0.2f;
    UnityEngine.AI.NavMeshAgent agent;
    public Transform groundCheck;
    public Slider healthBar;
    public GameObject deadUI;
    Animator animator;
    Rigidbody rb;
    bool isWalking, isRunning, isBackward, isFiring, isJumping, isDead;
    [SerializeField] private AudioSource coinSound;
    [SerializeField] private AudioSource deathSound;
    [SerializeField] private AudioSource runningSound;
    [SerializeField] private AudioSource hurtSound;
    [SerializeField] private AudioSource splashSound;

    GameObject currentGate;
    Dictionary<GameObject, bool> gateRotatedStates = new Dictionary<GameObject, bool>();

    List<GameObject> survivorsList = new List<GameObject>();
    public TextMeshProUGUI survivorCountText;
    [SerializeField] private AudioSource cheerSound;

    public GameObject rifle, shootingEffect;
    public float spread = 15f, shootingTime = 0.1f;
    public int survivorCount, bulletSpeed = 100;
    public Rigidbody bullet;
    public Transform shootingPoint;
    public static bool isShooting = false;
    public Camera mainCamera;
    public Camera zoomInCamera;
    [SerializeField] private AudioSource rifleSound;

    public GameObject shotgun;
    public Transform shotPoint;
    float[] spreadAngles = {-14, -10, -6, -2, 2, 6, 10, 14};
    public bool readyShot = true;
    [SerializeField] private AudioSource shotgunSound;

    public Slider shieldBar;
    public int ammoBuff = 0;
    public GameObject ammoUI;

    GameObject currentBomb;
    public TextMeshProUGUI grenadeCountText;
    public int grenadeCount;
    public GameObject grenade;
    public Rigidbody grenadeThrow;
    public Transform throwPoint;
    public GameObject throwAim;
    public bool isSpeedLocked = false;

    public GameObject congrateUI, collectGrenadeUI, saveSurvivorUI, openGateUI;

    void Start()
    {
        animator = GetComponent<Animator>();
        rb = GetComponent<Rigidbody>();
        isWalking = animator.GetBool("isWalking");
        isRunning = animator.GetBool("isRunning");
        isBackward = animator.GetBool("isBackward");
        isFiring = animator.GetBool("isFiring");
        isJumping = animator.GetBool("isJumping");
        isDead = animator.GetBool("isDead");

        healthBar.maxValue = 100;
        healthBar.value = 100;

        shieldBar.maxValue = 100;
        shieldBar.value = 0;

        agent = GetComponent<UnityEngine.AI.NavMeshAgent>();
    }
    private void FixedUpdate()
    {
        CheckGround();
    }
    private void CheckGround()
    {
        Collider[] colliders = Physics.OverlapSphere(groundCheck.transform.position, 0.2f);
        isGrounded = colliders.Length > 1;
    }
    void Update()
    {
        survivorCountText.text = "Survivor: " + survivorCount + "/10";
        grenadeCountText.text = "" + grenadeCount;

        float verticalInput = Input.GetAxis("Vertical");
        float horizontalInput = Input.GetAxis("Horizontal");

        // Move player
        transform.Translate(Vector3.forward * Time.deltaTime * agent.speed * verticalInput);
        transform.Rotate(Vector3.up, Time.deltaTime * turnspeed * horizontalInput);

        if (!isSpeedLocked)
        {
            if (!PauseMenu.Pausing)
            {
                if (Input.GetKey("space") && isGrounded && !Input.GetKey("s") && !Input.GetMouseButton(0))
                {
                    animator.SetBool("isJumping", true);
                    rb.AddForce(transform.up * jumpForce, ForceMode.Impulse);
                    rifle.transform.localEulerAngles = new Vector3(-142, -55, 123);                    
                }
                else if (Input.GetMouseButton(0) && !Input.GetKey("w") && !Input.GetKey("s") && !Input.GetKey("left shift") && rifle.activeSelf)
                {

                    animator.SetBool("isWalking", false);
                    animator.SetBool("isBackward", false);
                    animator.SetBool("isRunning", false);
                    animator.SetBool("isFiring", true);
                    animator.SetBool("isJumping", false);
                    agent.speed = 2.5f;
                    rifle.transform.localEulerAngles = new Vector3(-155, -70, 124);
                }
                else if (Input.GetMouseButtonDown(0) && !Input.GetKey("w") && !Input.GetKey("s") && !Input.GetKey("left shift") && shotgun.activeSelf && readyShot)
                {

                    animator.SetBool("isWalking", false);
                    animator.SetBool("isBackward", false);
                    animator.SetBool("isRunning", false);
                    animator.SetBool("isFiring", true);
                    animator.SetBool("isJumping", false);
                    agent.speed = 2.5f;
                }
                else if (Input.GetKey("w") && !isWalking)
                {
                    animator.SetBool("isWalking", true);
                    animator.SetBool("isBackward", false);
                    animator.SetBool("isRunning", false);
                    animator.SetBool("isFiring", false);
                    animator.SetBool("isJumping", false);
                    rifle.transform.localEulerAngles = new Vector3(-142, -55, 123);

                    if (Input.GetMouseButton(0) && !Input.GetKey("left shift") && rifle.activeSelf)
                    {
                        // Set firing animation and adjust rifle rotation
                        animator.SetBool("isFiring", true);
                        rifle.transform.localEulerAngles = new Vector3(-155, -80, 128);
                        agent.speed = 2.5f;
                    }
                    else if (Input.GetMouseButtonDown(0) && !Input.GetKey("left shift") && shotgun.activeSelf && readyShot)
                    {
                        // Set firing animation and adjust rifle rotation
                        animator.SetBool("isFiring", true);
                        agent.speed = 2.5f;
                    }
                    else if (Input.GetKey("left shift") && !isRunning)
                    {
                        // Set running animation and adjust speed
                        animator.SetBool("isRunning", true);
                        agent.speed = 15f;

                    }
                    else
                    {
                        // Reset firing animation and adjust rifle rotation
                        animator.SetBool("isFiring", false);

                        // Reset running animation and adjust speed
                        animator.SetBool("isRunning", false);
                        agent.speed = 7.5f;
                    }
                }
                else if (Input.GetKey("s"))
                {
                    animator.SetBool("isBackward", true);
                    animator.SetBool("isWalking", false);
                    animator.SetBool("isRunning", false);
                    animator.SetBool("isFiring", false);
                    animator.SetBool("isJumping", false);
                    rifle.transform.localEulerAngles = new Vector3(-142, -55, 123);

                    if (Input.GetMouseButton(0) && rifle.activeSelf)
                    {
                        animator.SetBool("isFiring", true);
                        rifle.transform.localEulerAngles = new Vector3(-155, -80, 128);
                        agent.speed = 2.5f;
                    }
                    else if (Input.GetMouseButtonDown(0) && shotgun.activeSelf && readyShot)
                    {
                        animator.SetBool("isFiring", true);
                        agent.speed = 2.5f;
                    }
                    else
                    {
                        agent.speed = 7.5f;
                        animator.SetBool("isFiring", false);
                    }
                }
                else if (grenade.activeSelf && Input.GetMouseButtonDown(0))
                {
                    StartCoroutine(StopMoving(3f));
                    isShooting = false;
                }
                else if (healthBar.value == 0)
                {
                    animator.SetBool("isDead", true);
                    deathSound.Play();
                    isSpeedLocked = true;
                    deadUI.SetActive(true);
                    agent.speed = 0;
                    turnspeed = 0;
                    rifle.SetActive(false);
                    shotgun.SetActive(false);
                    grenade.SetActive(false);
                    isShooting = false;
                }
                else
                {
                    animator.SetBool("isWalking", false);
                    animator.SetBool("isRunning", false);
                    animator.SetBool("isBackward", false);
                    animator.SetBool("isFiring", false);
                    animator.SetBool("isJumping", false);
                    animator.SetBool("isThrowing", false);

                    rifle.transform.localEulerAngles = new Vector3(-142, -55, 123);
                }


                if (Input.GetMouseButtonDown(0) && animator.GetBool("isFiring") && rifle.activeSelf)
                {
                    isShooting = true;
                    rifleSound.Play();
                    StartCoroutine(ShootCoroutine());
                }
                else if (!animator.GetBool("isFiring"))
                {
                    isShooting = false;
                    rifleSound.Stop();
                    StopCoroutine(ShootCoroutine());
                }


                if (Input.GetMouseButtonDown(1) && animator.GetBool("isFiring") && rifle.activeSelf)
                {
                    if (mainCamera.gameObject.activeSelf)
                    {
                        mainCamera.gameObject.SetActive(false);
                        zoomInCamera.gameObject.SetActive(true);
                    }
                    else
                    {
                        mainCamera.gameObject.SetActive(true);
                        zoomInCamera.gameObject.SetActive(false);
                    }
                }
                else if (!animator.GetBool("isFiring"))
                {
                    mainCamera.gameObject.SetActive(true);
                    zoomInCamera.gameObject.SetActive(false);
                }


                if (Input.GetKeyDown("g") && grenadeCount > 0 && !animator.GetBool("isFiring") && !Input.GetKey("left shift"))
                {
                    if (rifle.activeSelf)
                    {
                        rifle.SetActive(false);
                        grenade.SetActive(true);
                    }
                    else if (shotgun.activeSelf)
                    {
                        shotgun.SetActive(false);
                        grenade.SetActive(true);
                    }
                    else if (grenade.activeSelf)
                    {
                        rifle.SetActive(true);
                        grenade.SetActive(false);
                    }
                }
                else if (Input.GetKeyDown("g") && grenadeCount == 0 && !animator.GetBool("isFiring") && !Input.GetKey("left shift"))
                {
                    rifle.SetActive(true); 
                    grenade.SetActive(false);
                }

                if (Input.GetMouseButtonDown(1) && grenade.activeSelf)
                {
                    if (!throwAim.activeSelf)
                    {
                        throwAim.SetActive(true);
                    }
                    else
                    {
                        throwAim.SetActive(false);
                    }
                }
                else if (!grenade.activeSelf)
                {
                    throwAim.SetActive(false);
                }
            }

            if (Input.GetKeyDown("q") && rifle.activeSelf && !animator.GetBool("isFiring") && !Input.GetKey("left shift"))
            {
                if (rifle.activeSelf)
                {
                    rifle.SetActive(false);
                    shotgun.SetActive(true);
                }
                else if (shotgun.activeSelf)
                {
                    rifle.SetActive(true);
                    shotgun.SetActive(false);
                }
            }
            else if (Input.GetKeyDown("q") && shotgun.activeSelf && !animator.GetBool("isFiring") && !Input.GetKey("left shift"))
            {
                rifle.SetActive(true);
                shotgun.SetActive(false);
            }

            if (Input.GetMouseButtonDown(0) && animator.GetBool("isFiring") && shotgun.activeSelf && readyShot)
            {
                StartCoroutine(ShotgunTimer());
            }
        }

        if (animator.GetBool("isWalking") || animator.GetBool("isBackward"))
        {
            // Play the running sound
            if (!runningSound.isPlaying)
            {
                runningSound.Play();
            }
        }
        else
        {
            // Pause the running sound
            if (runningSound.isPlaying)
            {
                runningSound.Pause();
            }
        }


        if (ammoBuff == 1)
        {
            shootingTime = 0.05f;
            spread = 7.5f;
            float[] spreadAngles = { -10, -8, -6, -4, -2, 0, 2, 4, 6, 8 ,10};
            ammoUI.SetActive(true);
            StartCoroutine(SpeedToNormalAfterDelay(20f));
        }

        // Find the gate GameObject with the tag "grenade"
        GameObject[] bombs = GameObject.FindGameObjectsWithTag("grenade");

        // Find the nearest bomb within the range
        float minDistance = Mathf.Infinity;
        GameObject nearestBomb = null;

        foreach (GameObject bomb in bombs)
        {
            float distance = Vector3.Distance(transform.position, bomb.transform.position);
            if (distance < 3.0f && distance < minDistance)
            {
                minDistance = distance;
                nearestBomb = bomb;
            }
        }

        // If there's a nearest bomb within range, set it as the current bomb
        if (nearestBomb != null)
        {
            currentBomb = nearestBomb;
        }
        else
        {
            currentBomb = null;
        }

        // If the F key is pressed and there's a current bomb, destroy it
        if (Input.GetKeyDown(KeyCode.F) && currentBomb != null)
        {
            grenadeCount++;
            Destroy(currentBomb);
            currentBomb = null; // Reset currentBomb after destruction
        }

        // Find the gate GameObject with the tag "gate"
        GameObject[] gates = GameObject.FindGameObjectsWithTag("gate");

        if (gates.Length > 0)
        {
            foreach (GameObject gate in gates)
            {
                // Check if the gate is already in the dictionary, if not, add it with initial rotation state false
                if (!gateRotatedStates.ContainsKey(gate))
                {
                    gateRotatedStates.Add(gate, false);
                }

                if (Vector3.Distance(transform.position, gate.transform.position) < 3.0f)
                {
                    currentGate = gate;
                    if (Input.GetKeyDown(KeyCode.F) && currentGate != null)
                    {
                        // Check if the gate has already been rotated, if not, rotate it by -90 degrees
                        if (!gateRotatedStates[currentGate])
                        {
                            currentGate.transform.Rotate(0, -90, 0);
                            gateRotatedStates[currentGate] = true;
                        }
                        else
                        {
                            // Rotate it by 90 degrees if it has been rotated already
                            currentGate.transform.Rotate(0, 90, 0);
                            // Update the rotation state in the dictionary
                            gateRotatedStates[currentGate] = false;
                        }
                    }
                }
            }
        }

        GameObject[] survivors = GameObject.FindGameObjectsWithTag("survivor");

        if (survivors.Length > 0)
        {
            foreach (GameObject survivor in survivors)
            {
                if (Vector3.Distance(transform.position, survivor.transform.position) < 3.0f)
                {
                    if (Input.GetKeyDown(KeyCode.F))
                    {
                        if (!survivorsList.Contains(survivor))
                        {
                            survivorsList.Add(survivor);
                            survivorCount++;
                            survivor.tag = "savedSurvivor";

                            // Calculate target position with an additional offset for each new survivor
                            Vector3 targetPosition = transform.position - transform.forward * (0.5f + 0.5f * (survivorsList.Count - 1));
                            survivor.transform.position = targetPosition;
                        }

                        // Access the animator component of the survivor and set "isRun" boolean to true
                        Animator survivorAnimator = survivor.GetComponent<Animator>();
                        if (survivorAnimator != null)
                        {
                            survivorAnimator.SetBool("isRun", true);
                        }
                    }
                }
            }

            GameObject[] savedsurvivors = GameObject.FindGameObjectsWithTag("savedSurvivor");

            foreach (GameObject savedsurvivor in savedsurvivors)
            {
                if (survivorsList.Contains(savedsurvivor))
                {
                    Transform helpSound = savedsurvivor.transform.Find("helpSound");
                    if (helpSound != null)
                    {
                        helpSound.gameObject.SetActive(false);
                    }

                    // Move the survivor behind the player
                    Vector3 targetPosition = transform.position - transform.forward * (0.5f + 0.5f * (survivorsList.IndexOf(savedsurvivor)));
                    savedsurvivor.transform.position = Vector3.MoveTowards(savedsurvivor.transform.position, targetPosition, agent.speed * Time.deltaTime);

                    // Rotate the survivor to face the player's direction
                    Vector3 directionToPlayer = (transform.position - savedsurvivor.transform.position).normalized;
                    Quaternion lookRotation = Quaternion.LookRotation(new Vector3(directionToPlayer.x, 0, directionToPlayer.z));
                    savedsurvivor.transform.rotation = Quaternion.Slerp(savedsurvivor.transform.rotation, lookRotation, Time.deltaTime * turnspeed);
                }
            }
        }

        bool nearGrenade = IsNearObject(bombs);
        collectGrenadeUI.SetActive(nearGrenade);

        bool nearSurvivor = IsNearObject(survivors);
        saveSurvivorUI.SetActive(nearSurvivor);

        bool nearGate = IsNearObject(gates);
        openGateUI.SetActive(nearGate);
    }

    private bool IsNearObject(GameObject[] objects)
    {
        foreach (GameObject obj in objects)
        {
            if (Vector3.Distance(transform.position, obj.transform.position) < 3.0f)
            {
                return true;
            }
        }
        return false;
    }
    private IEnumerator ShootCoroutine()
    {
        while (isShooting)
        {
            Shoot(); // Fire the bullet            
            yield return new WaitForSeconds(shootingTime); // Adjust the delay between shots here
        }
    }

    private void Shoot()
    {
        Instantiate(shootingEffect, shootingPoint.position, shootingPoint.rotation);

        //Calculate spread
        float x = Random.Range(-spread, spread);
        float y = Random.Range(-spread, spread);

        Rigidbody p = Instantiate(bullet, shootingPoint.position, shootingPoint.rotation);
        Vector3 bulletDirection = Quaternion.Euler(x, y, 0) * shootingPoint.forward;
        p.velocity = bulletDirection * bulletSpeed;  
    }

    private IEnumerator ThrowBomb()
    {
        yield return new WaitForSeconds(1.5f);
        if (grenadeCount > 0)
        {
            Throw();
        }      
    }

    private void Throw()
    {
        Rigidbody t = Instantiate(grenadeThrow, throwPoint.position, throwPoint.rotation);
        Vector3 throwDirection = throwPoint.forward;
        t.velocity = throwDirection * 17.5f;

        grenadeCount--;
    }

    private IEnumerator ShotgunTimer()
    {
        readyShot = false;
        yield return new WaitForSeconds(0.25f);
        Shotgun();
        shotgunSound.Play();
        yield return new WaitForSeconds(0.5f);
        readyShot = true;
    }

    private void Shotgun()
    {
        Instantiate(shootingEffect, shotPoint.position, shotPoint.rotation);

        foreach (float angle in spreadAngles)
        {
            Rigidbody bulletInstance = Instantiate(bullet, shotPoint.position, shotPoint.rotation);
            Vector3 bulletDirection = Quaternion.Euler(0, angle, 0) * shotPoint.forward;
            bulletInstance.velocity = bulletDirection * bulletSpeed;
        }
    }


    void OnCollisionEnter(Collision col)
    {

        if (col.gameObject.CompareTag("normalZombie"))
        {
            int x = Random.Range(2, 4);
            StartCoroutine(WaitAndAttack(x, 1.5f));
        }
        else if (col.gameObject.CompareTag("ghoulZombie"))
        {
            int y = Random.Range(4, 7);
            StartCoroutine(WaitAndAttack(y, 1.0f));
        }
        else if (col.gameObject.CompareTag("bossZombie"))
        {
            int z = Random.Range(8, 10);
            StartCoroutine(WaitAndAttack(z, 0.5f));
        }
    }

    IEnumerator WaitAndAttack(int damage, float time)
    {   
        hurtSound.Play();

        if (shieldBar.value > 0)
        {
            shieldBar.value -= damage;
        }
        else if (shieldBar.value <= 0)
        {
            healthBar.value -= damage;
        }
        
        yield return new WaitForSeconds(time);
    }

    IEnumerator SpeedToNormalAfterDelay(float delay)
    {
        yield return new WaitForSeconds(delay);
        shootingTime = 0.1f;
        spread = 15f;
        float[] spreadAngles = {-14, -10, -6, -2, 2, 6, 10 , 14};
        ammoUI.SetActive(false);
        ammoBuff = 0;
    }

    IEnumerator StopMoving(float delay)
    {
        if (grenadeCount > 0)
        {
            animator.SetBool("isThrowing", true);
            isSpeedLocked = true; // Lock the speed
            agent.speed = 0;
            StartCoroutine(ThrowBomb());
        }
        
        yield return new WaitForSeconds(delay);

        animator.SetBool("isThrowing", false);
        agent.speed = 7.5f;
        isSpeedLocked = false; // Unlock the speed

        StopCoroutine(ThrowBomb());
    }

    private void OnTriggerEnter(Collider other)
    {
        if (other.CompareTag("splash"))
        {
            if (shieldBar.value > 0)
            {
                shieldBar.value -= 10;
            }
            else if (shieldBar.value <= 0)
            {
                healthBar.value -= 10;
            }
            splashSound.Play();
            hurtSound.Play();
        }

        if (other.CompareTag("goldCoin") || other.CompareTag("silverCoin"))
        {
            coinSound.Play();
        }

        if (other.CompareTag("finish"))
        {
            if (survivorCount >= 10 && CollectCoin.money >= 5000 && EnemyController.killCount >= 40)
            {
                // Activate congrats UI after 3 seconds
                Invoke("ActivateCongratsUI", 3f);
            }
        }
    }

    void ActivateCongratsUI()
    {
        GameObject[] savedsurvivors = GameObject.FindGameObjectsWithTag("savedSurvivor");

        if (savedsurvivors.Length > 0)
        {
            foreach (GameObject survivor in savedsurvivors)
            {
                // Access the animator component of the survivor and set "isRun" boolean to true
                Animator survivorAnimator = survivor.GetComponent<Animator>();
                if (survivorAnimator != null)
                {
                    survivorAnimator.SetBool("isCheer", true);
                }
            }
        }
        cheerSound.Play();
        // Activate congrats UI
        congrateUI.SetActive(true);
    }
}