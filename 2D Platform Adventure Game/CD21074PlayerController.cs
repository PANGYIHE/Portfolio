using System.Collections;
using System.Collections.Generic;
using UnityEditor.Build.Content;
using UnityEditor.Tilemaps;
using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;
using static UnityEditor.PlayerSettings;
namespace Platformer
{
    public class PlayerController : MonoBehaviour
    {
        public float movingSpeed;
        public float jumpForce;
        private float moveInput;
        private bool facingRight = false;
        private bool isGrounded;
        public Transform groundCheck;
        private Rigidbody2D rb;
        private Animator animator;

        public Image[] hearts;
        public int heartsCount = 3; 
        private float hurtForce = 8f;
        [SerializeField] public ParticleSystem deathPlayer;
        [SerializeField] private AudioSource jumpSoundEffect;
        [SerializeField] private AudioSource dieSoundEffect;
        [SerializeField] private AudioSource waterSoundEffect;
        [SerializeField] private GameObject gameOverCanvas;

        // Start is called before the first frame update
        void Start()
        {
            rb = GetComponent<Rigidbody2D>();
            animator = GetComponent<Animator>();
        }
        private void FixedUpdate()
        {
            CheckGround();
        }
        // Update is called once per frame
        void Update()
        {
            if (Input.GetButton("Horizontal"))
            {
                moveInput = Input.GetAxis("Horizontal");
                Vector3 direction = transform.right * moveInput;
                transform.position = Vector3.MoveTowards(transform.position, transform.position + direction, movingSpeed * Time.deltaTime);
                animator.SetInteger("playerState", 1); // Turn on run animation
            }
            else
            {
                if (isGrounded) animator.SetInteger("playerState", 0); //Turn on idle animation
            }
            if (Input.GetKeyDown(KeyCode.Space) && isGrounded)
            {
                jumpSoundEffect.Play();
                rb.AddForce(transform.up * jumpForce, ForceMode2D.Impulse);
            }
            if (!isGrounded) animator.SetInteger("playerState", 2); //Turn on jump animation
            if (facingRight == false && moveInput < 0)
            {
                Flip();
            }
            else if (facingRight == true && moveInput > 0)
            {
                Flip();
            }
        }
        private void Flip()
        {
            facingRight = !facingRight;
            Vector3 Scaler = transform.localScale;
            Scaler.x *= -1;
            transform.localScale = Scaler;
        }
        private void CheckGround()
        {
            Collider2D[] colliders = Physics2D.OverlapCircleAll(groundCheck.transform.position, 0.2f);
            isGrounded = colliders.Length > 1;
        }
        private void OnCollisionEnter2D(Collision2D other)
        {
            if (other.gameObject.tag == "Enemy" || other.gameObject.tag == "Water")
            {
                if (other.gameObject.tag == "Water")
                {
                    waterSoundEffect.Play();

                }
                if (heartsCount > 0)
                {
                    // Set the next heart as invisible
                    hearts[heartsCount - 1].enabled = false;

                    // Decrease the hearts count
                    heartsCount--;

                    if (other.gameObject.transform.position.x > transform.position.x)
                    {
                        rb.velocity = new Vector2(-hurtForce, rb.velocity.y);
                    }
                    else
                    {
                        rb.velocity = new Vector2(hurtForce, rb.velocity.y);
                    }
                    if (heartsCount == 0)
                    {
                        dieSoundEffect.Play();
                        Instantiate(deathPlayer, transform.position, Quaternion.identity);
                        gameObject.SetActive(false);
                        gameOverCanvas.gameObject.SetActive(true);
                    }
                }
            }
            
        }

        public void ApplySpeedBoost(float boostAmount, float duration)
        {
            StartCoroutine(SpeedBoostCoroutine(boostAmount, duration));
        }

        private IEnumerator SpeedBoostCoroutine(float boostAmount, float duration)
        {
            // Increase player's moving speed
            movingSpeed += boostAmount;

            // Wait for the specified duration
            yield return new WaitForSeconds(duration);

            // Restore original moving speed
            movingSpeed -= boostAmount;
        }

        public void ApplyJumpBoost(float boostAmount, float duration)
        {
            StartCoroutine(JumpBoostCoroutine(boostAmount, duration));
        }

        private IEnumerator JumpBoostCoroutine(float boostAmount, float duration)
        {
            // Increase player's moving speed
            jumpForce += boostAmount;

            // Wait for the specified duration
            yield return new WaitForSeconds(duration);

            // Restore original moving speed
            jumpForce -= boostAmount;
        }

        void LoadLevelGame()
        {
            SceneManager.LoadScene("LAB Game");
        }
    }
}
